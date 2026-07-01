<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Content\Article;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()->published()->latest('published_at')->paginate(9);

        return view('site.articles.index', compact('articles'));
    }

    public function show(Article $article): View
    {
        abort_unless($article->is_published, 404);

        $related = Article::query()->published()
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('site.articles.show', compact('article', 'related'));
    }
}
