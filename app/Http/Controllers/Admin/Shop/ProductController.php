<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->with('category')->latest()->paginate(20);

        return view('admin.shop.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = ProductCategory::query()->orderBy('name')->get();

        return view('admin.shop.products.form', ['product' => new Product, 'categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(6);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('shop/products', 'local');
        }

        Product::query()->create($validated);

        return redirect()->route('admin.shop.products.index')->with('success', 'Produit créé.');
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::query()->orderBy('name')->get();

        return view('admin.shop.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('shop/products', 'local');
        }

        $product->update($validated);

        return redirect()->route('admin.shop.products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.shop.products.index')->with('success', 'Produit supprimé.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'product_category_id' => ['required', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:200'],
            'type' => ['required', 'in:livre,fascicule,cours,pdf,logiciel,formation,abonnement'],
            'description' => ['nullable', 'string', 'max:3000'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['nullable', 'integer', 'min:0'],
            'is_digital' => ['sometimes', 'boolean'],
            'is_published' => ['sometimes', 'boolean'],
            'file' => ['nullable', 'file', 'max:51200'],
        ]);

        $validated['is_digital'] = $request->boolean('is_digital');
        $validated['is_published'] = $request->boolean('is_published');
        unset($validated['file']);

        return $validated;
    }
}
