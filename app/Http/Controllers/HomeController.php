<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Fabric;
use App\Models\Testimonial;
use App\Models\VideoReel;
use App\Models\PromotionalBanner;
use App\Models\Banner;
use App\Models\BudgetCard;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Get all descendant category IDs recursively
     */
    private function getAllChildCategoryIds($parentId)
    {
        $ids = [];
        $children = Category::where('parent_id', $parentId)->pluck('id')->toArray();
        
        foreach ($children as $childId) {
            $ids[] = $childId;
            // Recursively get children of this child
            $ids = array_merge($ids, $this->getAllChildCategoryIds($childId));
        }
        
        return $ids;
    }

    public function index()
    {
        // Fetch Hero Banners for main slider
        $heroBanners = Banner::active()
            ->orderBy('order')
            ->get();

        // Dynamic Main Categories for 'All-Time Favorites' Section
        $mainCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('show_in_homepage', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(2)
            ->get();

        // Load products for each category (including ALL sub-categories recursively)
        foreach ($mainCategories as $category) {
            // Get all Category IDs (Parent + ALL descendants)
            $allCategoryIds = [$category->id];
            $childIds = $this->getAllChildCategoryIds($category->id);
            $allCategoryIds = array_merge($allCategoryIds, $childIds);

            $products = Product::whereIn('category_id', $allCategoryIds)
                ->where('status', 'active')
                ->with('variants')
                ->latest()
                ->take(8)
                ->get();

            // Manually set the relation so the view code ($category->products) works as is
            $category->setRelation('products', $products);
        }

        // Fetch Homepage Collections
        $collections = \App\Models\Collection::where('is_active', true)
            ->where('show_in_homepage', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        // Fetch Active Testimonials
        $testimonials = Testimonial::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch Active Video Reels
        $videoReels = VideoReel::with('product')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch Circle Categories for header section
        $circleCategories = Category::where('is_active', true)
            ->where('show_in_circle', true)
            ->orderBy('circle_order')
            ->orderBy('name')
            ->get();

        // Fetch Promotional Banners by position
        $promotionalBanners = PromotionalBanner::currentlyActive()
            ->orderBy('position')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('position');

        // Fetch Fabrics for 'Shop By Fabric' Section
        $homepageFabrics = Fabric::where('is_active', true)
            ->where('show_in_homepage', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->take(4)
            ->get();

        // Get 4 products to show for ALL fabrics (same products in each tab)
        $fabricProducts = Product::where('status', 'active')
            ->with('variants')
            ->latest()
            ->take(4)
            ->get();

        // Set same products for each fabric
        foreach ($homepageFabrics as $fabric) {
            $fabric->setRelation('products', $fabricProducts);
        }

        // Fetch Budget Cards for 'Shop By Budget' Section
        $budgetCards = BudgetCard::active()->ordered()->get();

        return view('website.home', compact(
            'heroBanners',
            'mainCategories', 
            'collections', 
            'testimonials', 
            'videoReels', 
            'circleCategories',
            'promotionalBanners',
            'homepageFabrics',
            'budgetCards'
        ));
    }
}
