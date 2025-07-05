<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /**
     * Dashboard principal do admin
     */
    public function dashboard()
    {
        // Estatísticas gerais
        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'total_users' => User::count(),
            'total_orders' => 0, // Vamos implementar depois
        ];

        // Produtos mais populares (simulado por enquanto)
        $popularProducts = Product::orderBy('created_at', 'desc')->take(5)->get();
        
        // Produtos com estoque baixo
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->take(5)->get();

        return view('admin.dashboard', compact('stats', 'popularProducts', 'lowStockProducts'));
    }

    /**
     * Listar produtos
     */
    public function products(Request $request)
    {
        $query = Product::with('category');

        // Busca
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por categoria
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        // Filtro por status
        if ($request->status !== null) {
            $query->where('is_active', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);
        $categories = Category::where('is_active', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Criar produto
     */
    public function createProduct()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Salvar produto
     */
    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'quality_line' => 'required|in:basic,classic,premium',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Gerar SKU único
        $sku = $this->generateUniqueSku($request->name);

        $product = Product::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'sku' => $sku,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'quality_line' => $request->quality_line,
            'stock_quantity' => $request->stock_quantity,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.products')->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Editar produto
     */
    public function editProduct(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Atualizar produto
     */
    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'quality_line' => 'required|in:basic,classic,premium',
            'stock_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'quality_line' => $request->quality_line,
            'stock_quantity' => $request->stock_quantity,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.products')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Excluir produto
     */
    public function deleteProduct(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products')->with('success', 'Produto excluído com sucesso!');
    }

    /**
     * Listar categorias
     */
    public function categories()
    {
        $categories = Category::withCount('products')->orderBy('name')->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Criar categoria
     */
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    /**
     * Salvar categoria
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.categories')->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Editar categoria
     */
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Atualizar categoria
     */
    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => \Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.categories')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Excluir categoria
     */
    public function deleteCategory(Category $category)
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories')->with('error', 'Não é possível excluir categoria com produtos associados!');
        }

        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Categoria excluída com sucesso!');
    }

    /**
     * Simular pedidos (temporário)
     */
    public function orders()
    {
        // Por enquanto vamos simular alguns pedidos
        $orders = collect([
            [
                'id' => 'VG-2025-0001',
                'customer_name' => 'João Silva',
                'customer_email' => 'joao@email.com',
                'total' => 79.90,
                'status' => 'pending',
                'payment_method' => 'pix',
                'created_at' => now()->subHours(2),
            ],
            [
                'id' => 'VG-2025-0002',
                'customer_name' => 'Maria Santos',
                'customer_email' => 'maria@email.com',
                'total' => 159.80,
                'status' => 'paid',
                'payment_method' => 'boleto',
                'created_at' => now()->subHours(5),
            ],
        ]);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Gerar SKU único para produto
     */
    private function generateUniqueSku($productName)
    {
        // Limpar nome do produto e pegar as primeiras letras
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $productName);
        $prefix = strtoupper(substr($cleanName, 0, 3));
        
        // Se o prefixo for muito curto, usar VG como padrão
        if (strlen($prefix) < 2) {
            $prefix = 'VG';
        }
        
        $counter = 1;
        
        do {
            $sku = $prefix . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
            $exists = Product::where('sku', $sku)->exists();
            $counter++;
        } while ($exists);
        
        return $sku;
    }
} 