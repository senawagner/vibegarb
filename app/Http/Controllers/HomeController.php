<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Banner;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Banners ativos para o carousel
            $banners = Banner::active()->ordered()->get();

            // Produtos em destaque (otimizado)
            $featuredProducts = Product::where('is_active', true)
                ->where('is_featured', true)
                ->with(['category:id,name,slug'])
                ->select('id', 'name', 'slug', 'price', 'short_description', 'quality_line', 'category_id', 'is_featured')
                ->limit(8)
                ->get();

            // Categorias ativas (otimizado)
            $categories = Category::where('is_active', true)
                ->select('id', 'name', 'slug', 'description')
                ->orderBy('display_order')
                ->limit(6)
                ->get();

            // Produtos mais recentes (otimizado)
            $latestProducts = Product::where('is_active', true)
                ->with(['category:id,name,slug'])
                ->select('id', 'name', 'slug', 'price', 'short_description', 'quality_line', 'category_id', 'created_at')
                ->latest()
                ->limit(4)
                ->get();

            // Estatísticas básicas
            $stats = [
                'total_products' => Product::where('is_active', true)->count(),
                'total_categories' => Category::where('is_active', true)->count(),
                'featured_count' => $featuredProducts->count()
            ];

            return view('home', compact(
                'banners',
                'featuredProducts',
                'categories', 
                'latestProducts',
                'stats'
            ));
        } catch (\Exception $e) {
            \Log::error('HomeController error: ' . $e->getMessage());
            
            // Fallback com dados mínimos
            return view('home', [
                'banners' => collect([]),
                'featuredProducts' => collect([]),
                'categories' => collect([]),
                'latestProducts' => collect([]),
                'stats' => [
                    'total_products' => 0,
                    'total_categories' => 0,
                    'featured_count' => 0
                ]
            ]);
        }
    }
}
