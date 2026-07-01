<?php

namespace App\Services\Shop;

use App\Models\Shop\Product;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;

class CartService
{
    private const SESSION_KEY = 'shop.cart';

    public function __construct(private readonly SessionManager $session) {}

    /**
     * @return array<int, int> product_id => quantity
     */
    public function raw(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->raw();
        $cart[$product->id] = ($cart[$product->id] ?? 0) + $quantity;
        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->raw();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function isEmpty(): bool
    {
        return count($this->raw()) === 0;
    }

    /**
     * @return Collection<int, array{product: Product, quantity: int, total: float}>
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        return Product::query()
            ->whereIn('id', array_keys($cart))
            ->get()
            ->map(fn (Product $product) => [
                'product' => $product,
                'quantity' => $cart[$product->id],
                'total' => (float) $product->price * $cart[$product->id],
            ]);
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('total');
    }

    public function count(): int
    {
        return array_sum($this->raw());
    }
}
