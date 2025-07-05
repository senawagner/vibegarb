<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Lista todos os produtos com filtros avançados para MVP
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'productImages'])
            ->where('is_active', true);

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filtro por linha de qualidade (MVP)
        if ($request->filled('quality')) {
            $query->where('quality_line', $request->quality);
        }

        // Filtro por público alvo (MVP)
        if ($request->filled('audience')) {
            $query->where('target_audience', $request->audience);
        }

        // Filtro por faixa de preço
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Busca por nome ou descrição
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        // Ordenação
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'name':
                $query->orderBy('name', $sortOrder);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortOrder);
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('name', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Dados para filtros
        $categories = Category::active()->ordered()->get();
        $qualityLines = Product::distinct()->pluck('quality_line')->filter()->sort()->values();
        $audiences = Product::distinct()->pluck('target_audience')->filter()->sort()->values();
        
        // Faixa de preços para slider
        $priceRange = Product::active()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return view('products.index', compact(
            'products',
            'categories', 
            'qualityLines',
            'audiences',
            'priceRange'
        ))->with('filters', $request->only(['category', 'quality', 'audience', 'min_price', 'max_price', 'search', 'sort', 'order']));
    }

    /**
     * Exibe detalhes de um produto específico
     */
    public function show(Product $product)
    {
        // Versão simplificada mas com view Blade e CSS
        return view('products.show-simple', [
            'product' => $product,
            'relatedProducts' => collect([]),
            'availableColors' => ['Branca', 'Preta', 'Cinza'],
            'availableSizes' => ['P', 'M', 'G', 'GG'],
            'variantPrices' => []
        ]);
    }

    /**
     * API endpoint para busca rápida (autocomplete)
     */
    public function search(Request $request)
    {
        $term = $request->get('term');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $products = Product::with(['category', 'productImages'])
            ->where('is_active', true)
            ->where(function($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%')
                      ->orWhere('description', 'like', '%' . $term . '%')
                      ->orWhere('short_description', 'like', '%' . $term . '%');
            })
            ->limit(8)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->formatted_price,
                    'quality_line' => $product->quality_line,
                    'quality_description' => $product->quality_description,
                    'image' => $product->primary_image_url,
                    'url' => route('products.show', $product->slug)
                ];
            });

        return response()->json($products);
    }

    /**
     * API para filtros dinâmicos (AJAX)
     */
    public function filters(Request $request)
    {
        $data = [];

        // Se categoria foi selecionada, filtrar qualidades disponíveis
        if ($request->filled('category')) {
            $data['qualities'] = Product::active()
                ->whereHas('category', function($q) use ($request) {
                    $q->where('slug', $request->category);
                })
                ->distinct()
                ->pluck('quality_line')
                ->filter()
                ->sort()
                ->values();
        }

        // Se qualidade foi selecionada, filtrar cores/tamanhos disponíveis
        if ($request->filled('quality')) {
            $products = Product::active()->where('quality_line', $request->quality);
            
            $allColors = $products->get()->flatMap(function($product) {
                return $product->available_colors_array;
            })->unique()->sort()->values();
            
            $allSizes = $products->get()->flatMap(function($product) {
                return $product->available_sizes_array;
            })->unique()->sort()->values();
            
            $data['colors'] = $allColors;
            $data['sizes'] = $allSizes;
        }

        return response()->json($data);
    }

    /**
     * Produtos em destaque para homepage
     */
    public function featured()
    {
        $products = Product::with(['category', 'productImages'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->limit(8)
            ->get();

        return response()->json($products);
    }
}
