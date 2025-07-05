@extends('admin.layouts.app')

@section('title', 'Criar Categoria - Painel Admin')
@section('page-title', 'Criar Categoria')

@section('content')
<div class="p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900">Criar Nova Categoria</h2>
            <p class="text-gray-600">Organize seus produtos em categorias</p>
        </div>
        
        <a href="{{ route('admin.categories') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
                    @csrf
                    
                    <div class="space-y-6">
                        <!-- Nome -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nome da Categoria *</label>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
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
                                      rows="3"
                                      placeholder="Descrição opcional da categoria..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
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
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Categoria ativa (visível na loja)</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-200">
                        <div class="flex space-x-3">
                            <a href="{{ route('admin.categories') }}" 
                               class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                Criar Categoria
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Dicas -->
        <div class="space-y-6">
            <div class="bg-blue-50 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-4">💡 Dicas</h3>
                <div class="space-y-3 text-sm text-blue-800">
                    <div>
                        <h4 class="font-medium">Nome da Categoria:</h4>
                        <p>Use nomes claros e descritivos. Ex: "Camisetas Básicas", "Estampadas", "Oversized"</p>
                    </div>
                    <div>
                        <h4 class="font-medium">Organização:</h4>
                        <p>Pense na navegação do cliente. Categorias bem organizadas facilitam a busca.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Categorias Existentes</h3>
                <div class="space-y-2 text-sm">
                    @php
                        $existingCategories = \App\Models\Category::where('is_active', true)->take(5)->get();
                    @endphp
                    
                    @forelse($existingCategories as $category)
                        <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                            <span>{{ $category->name }}</span>
                            <span class="text-gray-500">{{ $category->products()->count() }} produtos</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Nenhuma categoria criada ainda</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 