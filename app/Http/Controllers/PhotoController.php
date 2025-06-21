<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Photo;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function index(): View
    {
        $parties = Party::with([
            'photos' => fn($q) => $q->latest()->take(8)
        ])->get();

        return view('gallery.index', compact('parties'));
    }

    public function partyGallery(Party $party): View
    {
        $photos = $party->photos()->with('user')->latest()->paginate(20);
        return view('gallery.party', compact('party', 'photos'));
    }

    public function store(Request $request, Party $party): RedirectResponse
    {
        $request->validate([
            'photos.*' => 'required|image|max:5120'
        ]);

        $uploaded = 0;

        foreach ($request->file('photos') as $file) {
            $hash = sha1_file($file->getRealPath());
            if (Photo::query()->where('hash', $hash)->exists()) {
                continue;
            }

            $path = $file->store('party_photos', 'public');
            $photo = new Photo([
                'party_id' => $party->id,
                'user_id' => $request->user()->id,
                'path' => $path,
                'hash' => $hash,
            ]);

            $photo->save();
            $uploaded++;
        }

        return back();
    }
}
