<?php

namespace App\Http\Controllers\Site\Shop;

use App\Http\Controllers\Controller;
use App\Models\Shop\Product;
use App\Models\Shop\ProductCategory;
use Illuminate\View\View;

class ShopController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::query()->withCount('products')->orderBy('name')->get();
        $featured = Product::query()->published()->latest()->take(8)->get();

        return view('shop.index', compact('categories', 'featured'));
    }

    public function category(ProductCategory $category): View
    {
        $products = Product::query()
            ->published()
            ->where('product_category_id', $category->id)
            ->paginate(12);

        return view('shop.category', compact('category', 'products'));
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_published, 404);

        return view('shop.show', compact('product'));
    }
}
