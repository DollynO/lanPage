<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Photo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index(): View
    {
        $parties = Party::with('photos')
            ->withCount('photos')
            ->orderByRaw('YEAR(start_date) DESC, start_date DESC')
            ->get();

        $parties->each(function ($party) {
            $party->previewPhotos = $party->photos->take(7);
        });

        return view('gallery.index', compact('parties'));
    }

    public function partyGallery(Party $party): View
    {
        $photos = $party->photos()->with('user')->latest()->paginate(20);
        return view('gallery.party', compact('party', 'photos'));
    }

    public function store(Request $req, Party $party)
    {
        $user = $req->user();
        abort_unless($user && $user->can_upload, 403);

        $files = $req->file('photos', []);
        $added = [];
        $skipped = [];
        $errors = []; // keyed by original name

        foreach ($files as $file) {
            $name = $file->getClientOriginalName();

            // basic validation per file
            if (!$file->isValid()) {
                $errors[$name] = 'Upload error (corrupted or interrupted).';
                continue;
            }

            if (!str_starts_with($file->getMimeType(), 'image/')) {
                $errors[$name] = 'Not an image.';
                continue;
            }

            if ($file->getSize() > 5 * 1024 * 1024) {
                $errors[$name] = 'Too large (max 5MB).';
                continue;
            }

            $hash = @sha1_file($file->getRealPath());
            if ($hash === false) {
                $errors[$name] = 'Could not hash file.';
                continue;
            }

            if (Photo::where('hash', $hash)->exists()) {
                $skipped[] = $name;
                continue;
            }

            // attempt store
            try {
                $path = $file->store('party_photos', 'public');
                Photo::create([
                    'party_id' => $party->id,
                    'user_id'  => $req->user()->id,
                    'path'     => $path,
                    'hash'     => $hash,
                ]);
                $added[] = $name;
            } catch (\Throwable $e) {
                $errors[$name] = 'Filesystem error: ' . $e->getMessage();
            }
        }

        if ($req->wantsJson() || $req->isXmlHttpRequest()) {
            return response()->json([
                'added'   => $added,
                'skipped' => $skipped,
                'errors'  => $errors,
            ], 200);
        }

        $msg = [];
        if ($added) $msg[] = count($added)." added";
        if ($skipped) $msg[] = count($skipped)." duplicates skipped";
        if ($errors) $msg[] = count($errors)." errors";
        return back()->with('status', implode('; ', $msg));
    }
}
