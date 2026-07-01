<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Content\Faq;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __invoke(): View
    {
        $faqs = Faq::query()->published()->orderBy('category')->orderBy('order')->get()->groupBy('category');

        return view('site.faq', compact('faqs'));
    }
}
