<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\GalleryAlbum;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryAlbumController extends Controller
{
    public function index(): View
    {
        $albums = GalleryAlbum::query()->withCount('items')->latest()->paginate(20);

        return view('admin.content.gallery-albums.index', compact('albums'));
    }

    public function create(): View
    {
        return view('admin.content.gallery-albums.form', ['album' => new GalleryAlbum]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(6);

        GalleryAlbum::query()->create($validated);

        return redirect()->route('admin.content.gallery-albums.index')->with('success', 'Album créé.');
    }

    public function edit(GalleryAlbum $galleryAlbum): View
    {
        $galleryAlbum->load('items');

        return view('admin.content.gallery-albums.form', ['album' => $galleryAlbum]);
    }

    public function update(Request $request, GalleryAlbum $galleryAlbum): RedirectResponse
    {
        $galleryAlbum->update($this->validated($request));

        if ($request->hasFile('items')) {
            foreach ($request->file('items') as $file) {
                $galleryAlbum->items()->create([
                    'path_or_url' => $file->store('gallery', 'local'),
                ]);
            }
        }

        return redirect()->route('admin.content.gallery-albums.edit', $galleryAlbum)->with('success', 'Album mis à jour.');
    }

    public function destroy(GalleryAlbum $galleryAlbum): RedirectResponse
    {
        $galleryAlbum->delete();

        return redirect()->route('admin.content.gallery-albums.index')->with('success', 'Album supprimé.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'type' => ['required', 'in:photo,video'],
        ]);
    }
}
