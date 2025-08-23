<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPhotoController extends Controller
{
    public function index(Request $req)
    {
        $q = Photo::with(['party','user'])->latest();

        if ($req->filled('party_id')) $q->where('party_id', $req->integer('party_id'));
        if ($req->filled('search')) {
            $s = $req->string('search');
            $q->where(function($w) use ($s){
                $w->where('path','like',"%{$s}%")
                    ->orWhereHas('user', fn($u)=>$u->where('name','like',"%{$s}%"));
            });
        }

        $photos  = $q->paginate(36)->withQueryString();
        $parties = Party::orderBy('start_date','desc')->get(['id','start_date']);
        return view('admin.photos.index', compact('photos','parties'));
    }

    public function update(Request $req, Photo $photo)
    {
        $data = $req->validate(['party_id' => 'required|exists:parties,id']);
        $photo->update(['party_id' => $data['party_id']]);
        return back()->with('status', 'Photo reassigned.');
    }

    public function destroy(Photo $photo)
    {
        if ($photo->path) {
            try {
                Storage::disk('public')->delete($photo->path);
            } catch (\Throwable $e) {}
        }
        $photo->delete();
        return back()->with('status', 'Photo deleted.');
    }

    public function bulkDestroy(Request $req)
    {
        $validated = $req->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:photos,id',
        ]);

        $photos = Photo::whereIn('id', $validated['ids'])->get();
        foreach ($photos as $p) {
            if ($p->path) { try { \Storage::disk('public')->delete($p->path); } catch (\Throwable $e) {} }
        }
        Photo::whereIn('id', $validated['ids'])->delete();

        return redirect()
            ->route('admin.photos.index')
            ->with('status', count($validated['ids']).' photos deleted.');
    }
}
