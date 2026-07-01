<?php

namespace App\Http\Controllers\Admin\Library;

use App\Http\Controllers\Controller;
use App\Models\Library\LibraryCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = LibraryCategory::query()->withCount('documents')->orderBy('name')->paginate(20);

        return view('admin.library.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.library.categories.form', ['category' => new LibraryCategory]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']);

        LibraryCategory::query()->create($validated);

        return redirect()->route('admin.library.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(LibraryCategory $category): View
    {
        return view('admin.library.categories.form', compact('category'));
    }

    public function update(Request $request, LibraryCategory $category): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.library.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(LibraryCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.library.categories.index')->with('success', 'Catégorie supprimée.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'icon' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
