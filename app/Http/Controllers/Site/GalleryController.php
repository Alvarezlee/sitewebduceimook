<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Content\GalleryAlbum;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(string $type): View
    {
        $albums = GalleryAlbum::query()->where('type', $type)->withCount('items')->latest()->paginate(12);

        return view('site.gallery.index', compact('albums', 'type'));
    }

    public function show(GalleryAlbum $album): View
    {
        $album->load('items');

        return view('site.gallery.show', compact('album'));
    }
}
