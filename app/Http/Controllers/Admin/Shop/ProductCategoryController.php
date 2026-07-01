<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::query()->withCount('products')->orderBy('name')->paginate(20);

        return view('admin.shop.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.shop.categories.form', ['category' => new ProductCategory]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:150']]);
        $validated['slug'] = Str::slug($validated['name']);

        ProductCategory::query()->create($validated);

        return redirect()->route('admin.shop.categories.index')->with('success', 'Catégorie créée.');
    }

    public function edit(ProductCategory $category): View
    {
        return view('admin.shop.categories.form', compact('category'));
    }

    public function update(Request $request, ProductCategory $category): RedirectResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:150']]);
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect()->route('admin.shop.categories.index')->with('success', 'Catégorie mise à jour.');
    }

    public function destroy(ProductCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.shop.categories.index')->with('success', 'Catégorie supprimée.');
    }
}
