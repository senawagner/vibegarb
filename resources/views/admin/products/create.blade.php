@extends('admin.layouts.app')

@section('title', 'Criar Produto - Painel Admin')
@section('page-title', 'Criar Produto')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Criar Novo Produto</h2>
            <p class="text-gray-600">Adicione um novo produto ao catálogo</p>
        </div>
        
        <a href="{{ route('admin.products') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow">
        <form action="{{ route('admin.products.store') }}" method="POST" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Nome -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Produto *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}"
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
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preço -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preço (R$) *</label>
                        <input type="number" 
                               name="price" 
                               value="{{ old('price') }}"
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
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <option value="classic" {{ old('quality_line') == 'classic' ? 'selected' : '' }}>Classic - Algodão penteado</option>
                            <option value="quality" {{ old('quality_line') == 'quality' ? 'selected' : '' }}>Quality - Custo-benefício</option>
                            <option value="prime" {{ old('quality_line') == 'prime' ? 'selected' : '' }}>Prime - Qualidade superior</option>
                            <option value="pima" {{ old('quality_line') == 'pima' ? 'selected' : '' }}>Pima - Algodão Pima premium</option>
                            <option value="estonada" {{ old('quality_line') == 'estonada' ? 'selected' : '' }}>Estonada - Aspecto vintage</option>
                            <option value="dry_sport" {{ old('quality_line') == 'dry_sport' ? 'selected' : '' }}>Dry Sport - Performance</option>
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
                               value="{{ old('stock_quantity', 0) }}"
                               min="0"
                               required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 @error('stock_quantity') border-red-500 @enderror">
                        @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   {{ old('is_active', true) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-gray-700">Produto ativo (visível na loja)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                <div class="flex space-x-3">
                    <a href="{{ route('admin.products') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Criar Produto
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Preview Card -->
    <div class="mt-6 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">💡 Dicas para Produtos</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <h4 class="font-medium text-gray-900">Nome do Produto:</h4>
                <p>Use nomes descritivos e claros. Ex: "Camiseta Básica Lisa Branca"</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">Descrição:</h4>
                <p>Inclua detalhes sobre material, corte, cuidados e características especiais.</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">Preço:</h4>
                <p>Considere custos, margem de lucro e preços da concorrência.</p>
            </div>
            <div>
                <h4 class="font-medium text-gray-900">Estoque:</h4>
                <p>Produtos com menos de 10 unidades aparecerão como "estoque baixo".</p>
            </div>
        </div>
    </div>
</div>
@endsection 