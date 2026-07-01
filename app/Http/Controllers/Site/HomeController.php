<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Business\Business;
use App\Models\Content\Article;
use App\Models\Content\Banner;
use App\Models\Content\Event;
use App\Models\Content\Partner;
use App\Models\Content\Review;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('site.home', [
            'banners' => Banner::query()->forPosition('home_carousel')->where('is_active', true)->orderBy('order')->get()
                ->filter->isCurrentlyActive(),
            'articles' => Article::query()->published()->latest('published_at')->take(3)->get(),
            'events' => Event::query()->published()->where('starts_at', '>=', now())->orderBy('starts_at')->take(3)->get(),
            'businesses' => Business::query()->published()->latest()->take(6)->get(),
            'reviews' => Review::query()->approved()->latest()->take(6)->get(),
            'partners' => Partner::query()->published()->orderBy('order')->get(),
        ]);
    }
}
