@extends('admin.layouts.app')

@section('title', 'Editar Categoria - Painel Admin')
@section('page-title', 'Editar Categoria')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Editar Categoria</h2>
            <p class="text-gray-600">{{ $category->name }}</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Ver na Loja
            </a>
            
            <a href="{{ route('admin.categories') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Nome -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Categoria *</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}"
                                   required
                                   placeholder="Ex: Camisetas Básicas"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <textarea name="description" 
                                      rows="4"
                                      placeholder="Descrição opcional da categoria..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Categoria ativa (visível na loja)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.categories') }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Atualizar Categoria
                            </button>
                        </div>
                        
                        <!-- Delete Button -->
                        @if($category->products()->count() == 0)
                        <form method="POST" action="{{ route('admin.categories.delete', $category) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.')"
                                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                                Excluir Categoria
                            </button>
                        </form>
                        @else
                        <div class="px-6 py-2 bg-gray-200 text-gray-500 rounded-lg cursor-not-allowed">
                            Não é possível excluir ({{ $category->products()->count() }} produtos)
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Informações -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Informações</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Criada em:</span>
                        <span>{{ $category->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Última atualização:</span>
                        <span>{{ $category->updated_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Slug:</span>
                        <span class="text-blue-600 break-all">{{ $category->slug }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total de produtos:</span>
                        <span class="font-semibold">{{ $category->products()->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Produtos da Categoria -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📦 Produtos nesta Categoria</h3>
                @php
                    $categoryProducts = $category->products()->take(5)->get();
                @endphp
                
                <div class="space-y-3">
                    @forelse($categoryProducts as $product)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center">
                                <span class="text-xs">👕</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($product->name, 25) }}</div>
                                <div class="text-xs text-gray-500">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
                            </div>
                        </div>
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="text-blue-600 hover:text-blue-800 text-xs">
                            Editar
                        </a>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">Nenhum produto nesta categoria ainda</p>
                    @endforelse
                    
                    @if($categoryProducts->count() > 0)
                    <div class="text-center pt-3">
                        <a href="{{ route('admin.products', ['category' => $category->id]) }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            Ver todos os {{ $category->products()->count() }} produtos
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Links Rápidos -->
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">🔗 Links Rápidos</h3>
                <div class="space-y-3">
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                       target="_blank"
                       class="block text-blue-600 hover:text-blue-800 text-sm">
                        👁️ Ver categoria na loja
                    </a>
                    <a href="{{ route('admin.products.create') }}?category={{ $category->id }}" 
                       class="block text-blue-600 hover:text-blue-800 text-sm">
                        ➕ Adicionar produto nesta categoria
                    </a>
                    <a href="{{ route('admin.products', ['category' => $category->id]) }}" 
                       class="block text-blue-600 hover:text-blue-800 text-sm">
                        📦 Gerenciar produtos desta categoria
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 