<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        // Get ALL active categories (both parent and child) with product counts
        $categories = Category::active()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('website.categories', compact('categories'));
    }
}
