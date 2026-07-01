<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    public function index(): View
    {
        $articles = Article::query()->latest()->paginate(20);

        return view('admin.content.articles.index', compact('articles'));
    }

    public function create(): View
    {
        return view('admin.content.articles.form', ['article' => new Article]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(6);
        $validated['author_id'] = Auth::id();
        $validated['published_at'] = $validated['is_published'] ? now() : null;

        Article::query()->create($validated);

        return redirect()->route('admin.content.articles.index')->with('success', 'Actualité créée.');
    }

    public function edit(Article $article): View
    {
        return view('admin.content.articles.form', compact('article'));
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($validated['is_published'] && ! $article->published_at) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.content.articles.index')->with('success', 'Actualité mise à jour.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('admin.content.articles.index')->with('success', 'Actualité supprimée.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:220'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'is_published' => ['sometimes', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:220'],
            'seo_description' => ['nullable', 'string', 'max:320'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
