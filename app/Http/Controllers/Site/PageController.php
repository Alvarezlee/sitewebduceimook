<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Content\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __invoke(string $slug): View
    {
        $page = Page::query()->published()->where('slug', $slug)->firstOrFail();

        return view('site.page', compact('page'));
    }
}
