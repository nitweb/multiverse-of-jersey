<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;

function shopProduct(string $name, array $attributes = []): Product
{
    return Product::create($attributes + [
        'name' => $name,
        'slug' => str($name)->slug()->toString(),
        'price' => 500,
        'stock' => 10,
        'status' => 'active',
    ]);
}

it('shows the shop page with its filters and products', function () {
    shopProduct('Retro Red Home Jersey');

    $this->get(route('shop'))
        ->assertOk()
        ->assertSee('Retro Red Home Jersey')
        ->assertSee('id="filter-form"', false)
        ->assertSee('id="shop-q"', false)
        ->assertSee('id="shop-results"', false);
});

it('hides inactive products from the shop', function () {
    shopProduct('Visible Jersey');
    shopProduct('Hidden Jersey', ['status' => 'draft']);

    $html = $this->getJson(route('shop'))->assertOk()->json('html');

    expect($html)->toContain('Visible Jersey')->not->toContain('Hidden Jersey');
});

it('filters the shop by brand and price', function () {
    $nike = Brand::create(['name' => 'Nike', 'slug' => 'nike', 'is_active' => true]);
    $puma = Brand::create(['name' => 'Puma', 'slug' => 'puma', 'is_active' => true]);
    shopProduct('Nike Cheap Jersey', ['brand_id' => $nike->id, 'price' => 1000]);
    shopProduct('Nike Pricey Jersey', ['brand_id' => $nike->id, 'price' => 5000]);
    shopProduct('Puma Jersey', ['brand_id' => $puma->id, 'price' => 1200]);

    $html = $this->getJson(route('shop', ['brand' => ['nike'], 'min' => 500, 'max' => 2000]))->assertOk()->json('html');

    expect($html)->toContain('Nike Cheap Jersey')
        ->not->toContain('Nike Pricey Jersey')
        ->not->toContain('Puma Jersey');
});

it('filters the shop by size and color', function () {
    $blueLarge = shopProduct('Blue Large Jersey');
    $redSmall = shopProduct('Red Small Jersey');
    ProductVariant::create(['product_id' => $blueLarge->id, 'size' => 'L', 'color' => 'Blue', 'stock' => 5]);
    ProductVariant::create(['product_id' => $redSmall->id, 'size' => 'S', 'color' => 'Red', 'stock' => 5]);

    $html = $this->getJson(route('shop', ['size' => ['L'], 'color' => ['Blue']]))->assertOk()->json('html');

    expect($html)->toContain('Blue Large Jersey')->not->toContain('Red Small Jersey');
});

it('searches the shop by product name', function () {
    shopProduct('Arsenal Away Jersey');
    shopProduct('Chelsea Home Jersey');

    $html = $this->getJson(route('shop', ['q' => 'arsenal']))->assertOk()->json('html');

    expect($html)->toContain('Arsenal Away Jersey')->not->toContain('Chelsea Home Jersey');
});

it('sorts the shop by price', function () {
    shopProduct('Cheap Jersey', ['price' => 900]);
    shopProduct('Pricey Jersey', ['price' => 4500]);

    $low = $this->getJson(route('shop', ['sort' => 'lo']))->json('html');
    $high = $this->getJson(route('shop', ['sort' => 'hi']))->json('html');

    expect(strpos($low, 'Cheap Jersey'))->toBeLessThan(strpos($low, 'Pricey Jersey'))
        ->and(strpos($high, 'Pricey Jersey'))->toBeLessThan(strpos($high, 'Cheap Jersey'));
});

it('paginates the shop twelve products at a time', function () {
    foreach (range(1, 13) as $number) {
        shopProduct("Numbered Jersey {$number}");
    }

    $first = $this->getJson(route('shop'))->assertOk()->json('html');
    $second = $this->getJson(route('shop', ['page' => 2]))->assertOk()->json('html');

    expect($first)->toContain('data-total="13"')->toContain('rel="next"')
        ->and(substr_count($first, '<article class="pcard'))->toBe(12)
        ->and(substr_count($second, '<article class="pcard'))->toBe(1);
});

it('lists only the products of a category on its page', function () {
    $shirts = Category::create(['name' => 'Shirts', 'slug' => 'shirts', 'is_active' => true]);
    shopProduct('Cotton Shirt', ['category_id' => $shirts->id]);
    shopProduct('Leather Wallet');

    $this->get(route('category.show', $shirts))
        ->assertOk()
        ->assertSee('Cotton Shirt')
        ->assertDontSee('Leather Wallet');
});

it('returns 404 for an inactive category', function () {
    $category = Category::create(['name' => 'Hidden', 'slug' => 'hidden', 'is_active' => false]);

    $this->get(route('category.show', $category))->assertNotFound();
});

it('shows an empty state when nothing matches', function () {
    shopProduct('Arsenal Away Jersey');

    $html = $this->getJson(route('shop', ['q' => 'zzzz']))->assertOk()->json('html');

    expect($html)->toContain('No products match your filters')->toContain('data-total="0"');
});
