<?php

namespace App\Http\Controllers\Photos;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Models\Photo;
use Filament\Notifications\Notification;
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

        if (!empty($errors)) {
            Notification::make()
                ->title('Import Errors')
                ->icon('heroicon-o-document-text')
                ->danger()
                ->body(
                    "Some items could not be processed:\n\n" .
                    "Errors: " . count($errors) . "\n" .
                    "- " . implode("\n- ", $errors)
                )
                ->send();
        } elseif (!empty($skipped)) {
            Notification::make()
                ->title('Saved successfully')
                ->info()
                ->icon('heroicon-o-document-text')
                ->body(
                    sprintf(
                        "Added: %d | Skipped: %d | Errors: %d",
                        count($added),
                        count($skipped),
                        count($errors)
                    )
                )
                ->send();
        } else {
            Notification::make()
                ->title('Saved successfully')
                ->success()
                ->icon('heroicon-o-document-text')
                ->body(
                    sprintf(
                        "Added: %d | Skipped: %d | Errors: %d",
                        count($added),
                        count($skipped),
                        count($errors)
                    )
                )
                ->iconColor('success')
                ->send();
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
