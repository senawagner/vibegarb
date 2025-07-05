@extends('admin.layouts.app')

@section('title', 'Dashboard - Painel Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="p-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Produtos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Produtos</h2>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_products'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Categorias -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Categorias</h2>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_categories'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Usuários -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Usuários</h2>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_users'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pedidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Pedidos</h2>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</p>
                    <p class="text-xs text-gray-500">Em breve</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Ações Rápidas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.products.create') }}" 
                   class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Adicionar Produto
                </a>
                
                <a href="{{ route('admin.categories.create') }}" 
                   class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Adicionar Categoria
                </a>

                <a href="{{ route('admin.orders') }}" 
                   class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2"></path>
                    </svg>
                    Ver Pedidos
                </a>
            </div>
        </div>

        <!-- Produtos Recentes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Produtos Recentes</h3>
            <div class="space-y-3">
                @forelse($popularProducts as $product)
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                        <span class="text-lg">👕</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">R$ {{ number_format($product->price, 2, ',', '.') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 bg-{{ $product->is_active ? 'green' : 'red' }}-100 text-{{ $product->is_active ? 'green' : 'red' }}-800 rounded">
                        {{ $product->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Nenhum produto encontrado</p>
                @endforelse
            </div>
        </div>

        <!-- Estoque Baixo -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estoque Baixo</h3>
            <div class="space-y-3">
                @forelse($lowStockProducts as $product)
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-red-100 rounded flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                        <p class="text-xs text-red-600">{{ $product->stock_quantity }} unidades</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Todos os produtos com estoque ok</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Links Úteis -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Links Úteis</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.products') }}" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="text-2xl mb-2">📦</div>
                <div class="text-sm font-medium">Gerenciar Produtos</div>
            </a>
            
            <a href="{{ route('admin.categories') }}" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="text-2xl mb-2">📁</div>
                <div class="text-sm font-medium">Gerenciar Categorias</div>
            </a>
            
            <a href="{{ route('home') }}" target="_blank" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="text-2xl mb-2">🏪</div>
                <div class="text-sm font-medium">Ver Loja</div>
            </a>
            
            <a href="{{ route('admin.orders') }}" class="text-center p-4 border rounded-lg hover:bg-gray-50 transition">
                <div class="text-2xl mb-2">📋</div>
                <div class="text-sm font-medium">Pedidos</div>
            </a>
        </div>
    </div>
</div>
@endsection 