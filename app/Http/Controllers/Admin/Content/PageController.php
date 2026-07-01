<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Content\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::query()->orderBy('title')->paginate(20);

        return view('admin.content.pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin.content.pages.form', ['page' => new Page]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['title']);

        Page::query()->create($validated);

        return redirect()->route('admin.content.pages.index')->with('success', 'Page créée.');
    }

    public function edit(Page $page): View
    {
        return view('admin.content.pages.form', compact('page'));
    }

    public function update(Request $request, Page $page): RedirectResponse
    {
        $page->update($this->validated($request));

        return redirect()->route('admin.content.pages.index')->with('success', 'Page mise à jour.');
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.content.pages.index')->with('success', 'Page supprimée.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'content' => ['required', 'string'],
            'seo_title' => ['nullable', 'string', 'max:220'],
            'seo_description' => ['nullable', 'string', 'max:320'],
            'is_published' => ['sometimes', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }
}
