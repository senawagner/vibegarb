<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Services\DimonaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Log;

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
     * Listar pedidos
     */
    public function orders()
    {
        // Busca todos os pedidos reais do banco de dados, ordenados pelo mais recente
        $orders = Order::with('items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Envia o pedido para produção, integrando com o DimonaService.
     */
    public function sendOrderToProduction(Order $order, DimonaService $dimonaService)
    {
        // 1. Validar se o pedido pode ser enviado (status 'pago' e ainda não enviado)
        if ($order->status !== 'paid' || !is_null($order->sent_to_supplier_at)) {
            return back()->with('error', 'Este pedido não está apto para ser enviado para produção.');
        }

        try {
            // 2. Chamar o serviço que encapsula a lógica da API
            Log::info("Iniciando envio do pedido {$order->order_number} para a Dimona.");
            $dimonaResponse = $dimonaService->sendOrder($order);

            // 3. Verificar a resposta e atualizar o pedido local
            // A API da Dimona retorna um JSON como: {"order":"064-435-556"}
            if (isset($dimonaResponse['order'])) {
                $order->update([
                    'supplier_order_id' => $dimonaResponse['order'],
                    'sent_to_supplier_at' => now(),
                    'status' => 'in_production',
                    'supplier_status' => 'sent', // Um status mais claro
                ]);

                Log::info("Pedido {$order->order_number} enviado com sucesso para a Dimona.", [
                    'dimona_order_id' => $dimonaResponse['order']
                ]);

                return back()->with('success', "Pedido enviado para produção! ID Dimona: {$dimonaResponse['order']}");
            } else {
                 Log::warning('Resposta da API da Dimona não continha o ID do pedido.', [
                    'order_number' => $order->order_number,
                    'response' => $dimonaResponse
                ]);
                return back()->with('error', 'Resposta inesperada do fornecedor. O pedido não foi enviado.');
            }

        } catch (\Illuminate\Http\Client\RequestException $e) {
            // Erro específico de HTTP (ex: 401, 422, 500)
            Log::error('Erro de requisição HTTP ao enviar pedido para a Dimona.', [
                'order_number' => $order->order_number,
                'status_code' => $e->response->status(),
                'response_body' => $e->response->body(),
                'error_message' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Erro na comunicação com o fornecedor (' . $e->response->status() . '). Verifique os logs.');

        } catch (Exception $e) {
            // Outros erros (ex: timeout, erro de configuração)
            Log::error('Falha geral ao enviar pedido para a Dimona.', [
                'order_number' => $order->order_number,
                'error_message' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Ocorreu uma falha inesperada. Tente novamente mais tarde.');
        }
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