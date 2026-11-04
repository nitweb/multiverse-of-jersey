<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\FlashDeal;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const RELATIONS = ['images', 'variants', 'category', 'brand'];

    public function index(): View
    {
        $homeCategories = Category::homeList();

        if ($homeCategories->isEmpty()) {
            $homeCategories = Category::menuTree();
        }

        $categorySections = $homeCategories
            ->map(fn (Category $category) => [
                'category' => $category,
                'products' => Product::active()->inCategory($category->id)->with(self::RELATIONS)->latest('id')->limit(8)->get(),
            ])
            ->filter(fn (array $section) => $section['products']->isNotEmpty())
            ->take(4)
            ->values();

        $flashDeal = FlashDeal::live()->firstWhere('show_on_home', true);
        $flashProducts = $flashDeal?->activeProducts(4) ?? collect();

        if ($flashProducts->isNotEmpty()) {
            (new EloquentCollection($flashProducts->all()))->loadAvg('reviews', 'rating')->loadCount('reviews');
        }

        $newProducts = Product::active()->where('is_new', true)->with(self::RELATIONS)->latest('id')->limit(8)->get();

        if ($newProducts->isEmpty()) {
            $newProducts = Product::active()->with(self::RELATIONS)->latest('id')->limit(8)->get();
        }

        $newProducts->loadAvg('reviews', 'rating')->loadCount('reviews');

        return view('frontend.index', [
            'banners' => Banner::active()->get(),
            'homeCategories' => $homeCategories,
            'homeBrands' => Brand::active()->featured()->get(),
            'flashDeal' => $flashDeal,
            'flashProducts' => $flashProducts,
            'featuredProducts' => Product::active()->where('is_featured', true)->with(self::RELATIONS)->latest('id')->limit(8)->get(),
            'newProducts' => $newProducts,
            'categorySections' => $categorySections,
        ]);
    }
}
