<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Promo;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CustomerFeedback;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const RELATIONS = ['images', 'variants', 'category', 'brand'];

    public function index(): View
    {
        $homeCategories = Category::homeList();

        $categorySections = Category::homeList('show_products_on_home')
            ->map(fn (Category $category) => [
                'category' => $category,
                'products' => Product::active()->inCategory($category->id)->with(self::RELATIONS)->latest('id')->limit(8)->get(),
            ])
            ->filter(fn (array $section) => $section['products']->isNotEmpty())
            ->values();

        $featuredProducts = Product::active()->where('is_featured', true)->with(self::RELATIONS)->latest('id')->limit(8)->get();
        $featuredProducts->loadAvg('reviews', 'rating')->loadCount('reviews');

        if ($categorySections->isNotEmpty()) {
            (new EloquentCollection($categorySections->flatMap(fn (array $section) => $section['products']->all())->all()))
                ->loadAvg('reviews', 'rating')
                ->loadCount('reviews');
        }

        $newProducts = Product::active()->where('is_new', true)->with(self::RELATIONS)->latest('id')->limit(8)->get();

        if ($newProducts->isEmpty()) {
            $newProducts = Product::active()->with(self::RELATIONS)->latest('id')->limit(8)->get();
        }

        $newProducts->loadAvg('reviews', 'rating')->loadCount('reviews');

        return view('frontend.index', [
            'banners' => Banner::active()->get(),
            'promos' => Promo::whereNotNull('image')->orderBy('slot')->get(),
            'homeCategories' => $homeCategories,
            'homeBrands' => Brand::active()->featured()->get(),
            'featuredProducts' => $featuredProducts,
            'feedbacks' => CustomerFeedback::active()->get(),
            'newProducts' => $newProducts,
            'categorySections' => $categorySections,
        ]);
    }
}
