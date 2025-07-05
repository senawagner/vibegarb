@extends('admin.layouts.app')

@section('title', 'Editar Produto - Painel Admin')
@section('page-title', 'Editar Produto')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Editar Produto</h2>
            <p class="text-gray-600">{{ $product->name }}</p>
        </div>
        
        <div class="flex space-x-3">
            <a href="{{ route('products.show', $product->slug) }}" 
               target="_blank"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                Ver na Loja
            </a>
            
            <a href="{{ route('admin.products') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Nome -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Produto *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name', $product->name) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição *</label>
                        <textarea name="description" 
                                  rows="4"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preço -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preço (R$) *</label>
                        <input type="number" 
                               name="price" 
                               value="{{ old('price', $product->price) }}"
                               step="0.01"
                               min="0"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('price') border-red-500 @enderror">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Categoria -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria *</label>
                        <select name="category_id" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('category_id') border-red-500 @enderror">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Linha de Qualidade -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Linha de Qualidade *</label>
                        <select name="quality_line" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('quality_line') border-red-500 @enderror">
                            <option value="">Selecione a qualidade</option>
                            <option value="classic" {{ old('quality_line', $product->quality_line) == 'classic' ? 'selected' : '' }}>Classic - Algodão penteado</option>
                            <option value="quality" {{ old('quality_line', $product->quality_line) == 'quality' ? 'selected' : '' }}>Quality - Custo-benefício</option>
                            <option value="prime" {{ old('quality_line', $product->quality_line) == 'prime' ? 'selected' : '' }}>Prime - Qualidade superior</option>
                            <option value="pima" {{ old('quality_line', $product->quality_line) == 'pima' ? 'selected' : '' }}>Pima - Algodão Pima premium</option>
                            <option value="estonada" {{ old('quality_line', $product->quality_line) == 'estonada' ? 'selected' : '' }}>Estonada - Aspecto vintage</option>
                            <option value="dry_sport" {{ old('quality_line', $product->quality_line) == 'dry_sport' ? 'selected' : '' }}>Dry Sport - Performance</option>
                        </select>
                        @error('quality_line')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Estoque -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade em Estoque *</label>
                        <input type="number" 
                               name="stock_quantity" 
                               value="{{ old('stock_quantity', $product->stock_quantity) }}"
                               min="0"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('stock_quantity') border-red-500 @enderror">
                        @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if($product->stock_quantity < 10)
                            <p class="mt-1 text-sm text-orange-600">⚠️ Estoque baixo!</p>
                        @endif
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Produto ativo (visível na loja)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Atualizar Produto
                    </button>
                </div>
                
                <!-- Delete Button -->
                <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.')"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Excluir Produto
                    </button>
                </form>
            </div>
        </form>
    </div>

    <!-- Product Info -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Informações</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Criado em:</span>
                    <span>{{ $product->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Última atualização:</span>
                    <span>{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Slug:</span>
                    <span class="text-purple-600">{{ $product->slug }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📈 Performance</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Visualizações:</span>
                    <span>-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Vendas:</span>
                    <span>-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Avaliações:</span>
                    <span>-</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">🔗 Links Rápidos</h3>
            <div class="space-y-3">
                <a href="{{ route('products.show', $product->slug) }}" 
                   target="_blank"
                   class="block text-blue-600 hover:text-blue-800 text-sm">
                    👁️ Ver na loja
                </a>
                <a href="{{ route('admin.categories.edit', $product->category) }}" 
                   class="block text-purple-600 hover:text-purple-800 text-sm">
                    📁 Editar categoria
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 