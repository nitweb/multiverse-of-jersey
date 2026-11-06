<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product, CartService $cart): View
    {
        abort_unless($product->status === 'active', 404);

        $product->load(['images', 'variants', 'category.parent', 'brand']);

        $trail = [];

        for ($node = $product->category; $node; $node = $node->parent) {
            array_unshift($trail, $node);
        }

        $related = Product::active()
            ->with(['images', 'variants', 'category', 'brand'])
            ->whereKeyNot($product->id)
            ->when($product->category_id, fn ($q) => $q->where('category_id', $product->category_id))
            ->latest('id')
            ->limit(4)
            ->get();

        if ($related->count() < 4) {
            $related = $related->concat(
                Product::active()
                    ->with(['images', 'variants', 'category', 'brand'])
                    ->whereKeyNot($product->id)
                    ->whereNotIn('id', $related->pluck('id'))
                    ->latest('id')
                    ->limit(4 - $related->count())
                    ->get()
            );
        }

        $gallery = collect([$product->front_image_url, $product->back_image_url])
            ->merge($product->images->map->url)
            ->filter()
            ->unique()
            ->values();

        $variants = $product->variants->map(fn ($variant) => [
            'id' => $variant->id,
            'size' => $variant->size,
            'color' => $variant->color,
            'price' => $variant->price !== null ? (float) $variant->price : (float) $product->price,
            'stock' => (int) $variant->stock,
            'sku' => $variant->sku,
        ])->values();

        return view('frontend.details.product_details', [
            'product' => $product,
            'trail' => $trail,
            'related' => $related,
            'gallery' => $gallery,
            'variants' => $variants,
            'sizes' => $product->variants->pluck('size')->filter()->unique()->values(),
            'colors' => $product->variants->pluck('color')->filter()->unique()->values(),
            'zones' => collect($cart->zones())->filter(fn ($zone) => $zone['enabled'] ?? true),
        ]);
    }
}
