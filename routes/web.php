<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Página principal - homepage da loja Vibe Garb
Route::get('/', [HomeController::class, 'index'])->name('home');

// Dashboard - redireciona para o local apropriado baseado no tipo de usuário
Route::middleware('auth')->get('/dashboard', function () {
    if (Auth::user()->email === 'admin@vibegarb.com') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('profile.edit');
})->name('dashboard');

// Rotas de produtos (públicas)
Route::prefix('produtos')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
});

// TESTE TEMPORÁRIO - produto simples
Route::get('/teste-produto/{slug}', function($slug) {
    $product = \App\Models\Product::where('slug', $slug)->first();
    if (!$product) abort(404);
    
    return "
    <h1>PRODUTO TESTE: {$product->name}</h1>
    <p>Preço: R$ " . number_format($product->price, 2, ',', '.') . "</p>
    <p>Descrição: {$product->short_description}</p>
    <a href='/produtos'>Voltar</a>
    ";
});

// Rotas do carrinho
Route::prefix('carrinho')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/adicionar', [CartController::class, 'add'])->name('add');
    Route::patch('/atualizar/{itemKey}', [CartController::class, 'update'])->name('update');
    Route::delete('/remover/{itemKey}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/limpar', [CartController::class, 'clear'])->name('clear');
});

// Rotas de checkout
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/processar', [CheckoutController::class, 'process'])->name('process');
    Route::get('/sucesso', [CheckoutController::class, 'success'])->name('success');
    
    // APIs para checkout
    Route::post('/buscar-cep', [CheckoutController::class, 'searchZipcode'])->name('search_zipcode');
    Route::post('/calcular-frete', [CheckoutController::class, 'calculateShipping'])->name('calculate_shipping');
});

// Painel Administrativo (protegido)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Dropshipping Dashboard
    Route::prefix('dropshipping')->name('dropshipping.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\DropshippingController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [App\Http\Controllers\Admin\DropshippingController::class, 'index'])->name('orders');
        Route::get('/ready-for-supplier', [App\Http\Controllers\Admin\DropshippingController::class, 'readyForSupplier'])->name('ready_for_supplier');
        Route::get('/in-production', [App\Http\Controllers\Admin\DropshippingController::class, 'inProduction'])->name('in_production');
        Route::get('/shipped-by-supplier', [App\Http\Controllers\Admin\DropshippingController::class, 'shippedBySupplier'])->name('shipped_by_supplier');
        Route::get('/financial-report', [App\Http\Controllers\Admin\DropshippingController::class, 'financialReport'])->name('financial_report');
        
        // Ações de pedidos
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\DropshippingController::class, 'show'])->name('show_order');
        
        // Nova rota para enviar o pedido para o fornecedor
        Route::post('/orders/{order}/send-to-supplier', [App\Http\Controllers\Admin\DropshippingController::class, 'sendToSupplier'])->name('send_to_supplier');

        Route::post('/orders/{order}/confirm-supplier', [App\Http\Controllers\Admin\DropshippingController::class, 'confirmWithSupplier'])->name('confirm_supplier');
        Route::post('/orders/{order}/mark-paid', [App\Http\Controllers\Admin\DropshippingController::class, 'markAsPaidToSupplier'])->name('mark_paid');
        Route::post('/orders/{order}/update-production-status', [App\Http\Controllers\Admin\DropshippingController::class, 'updateProductionStatus'])->name('update_production_status');
        Route::post('/orders/{order}/update-tracking', [App\Http\Controllers\Admin\DropshippingController::class, 'updateTracking'])->name('update_tracking');
        Route::post('/orders/{order}/update-supplier-notes', [App\Http\Controllers\Admin\DropshippingController::class, 'updateSupplierNotes'])->name('update_supplier_notes');
        Route::post('/orders/{order}/update-internal-notes', [App\Http\Controllers\Admin\DropshippingController::class, 'updateInternalNotes'])->name('update_internal_notes');
        
        // Exportação
        Route::post('/export-for-supplier', [App\Http\Controllers\Admin\DropshippingController::class, 'exportForSupplier'])->name('export_for_supplier');
    });
    
    // Produtos
    Route::get('/produtos', [AdminController::class, 'products'])->name('products');
    Route::get('/produtos/criar', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/produtos', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/produtos/{product}/editar', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/produtos/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/produtos/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
    
    // Categorias
    Route::get('/categorias', [AdminController::class, 'categories'])->name('categories');
    Route::get('/categorias/criar', [AdminController::class, 'createCategory'])->name('categories.create');
    Route::post('/categorias', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::get('/categorias/{category}/editar', [AdminController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categorias/{category}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categorias/{category}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    
    // Pedidos
    Route::get('/pedidos', [AdminController::class, 'orders'])->name('orders');
    
    // Banners
    Route::resource('banners', App\Http\Controllers\Admin\BannerController::class);
    Route::post('/banners/{banner}/toggle-status', [App\Http\Controllers\Admin\BannerController::class, 'toggleStatus'])->name('banners.toggle_status');
});

// API Routes para funcionalidades do catálogo e carrinho
Route::prefix('api')->group(function () {
    // Produtos
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::get('products/filters', [ProductController::class, 'filters'])->name('products.filters');
    Route::get('products/featured', [ProductController::class, 'featured'])->name('products.featured');
    
    // Carrinho
    Route::get('cart/count', [CartController::class, 'count'])->name('cart.count');
});

// Rotas de perfil (requer autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// TESTE TEMPORÁRIO - produto simples
Route::get('/teste-produto/{slug}', function($slug) {
    $product = \App\Models\Product::where('slug', $slug)->first();
    if (!$product) abort(404);
    
    return "<h1>PRODUTO TESTE: {$product->name}</h1><p>Preço: R$ " . number_format($product->price, 2, ',', '.') . "</p><a href='/produtos'>Voltar</a>";
});

// Rotas de autenticação (geradas pelo Breeze)
require __DIR__.'/auth.php';
