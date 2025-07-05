@extends('layouts.app')

@section('title', 'Produtos - Vibe Garb')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header do Catálogo -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Nossos Produtos</h1>
            <p class="text-gray-600">Encontre a camiseta perfeita para o seu estilo</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar de Filtros -->
            <div class="lg:w-64 space-y-6">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Filtros</h3>
                    
                    <form method="GET" action="{{ route('products.index') }}" id="filter-form">
                        <!-- Busca -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Produto</label>
                            <input type="text" 
                                   name="search" 
                                   value="{{ $filters['search'] ?? '' }}"
                                   placeholder="Nome ou descrição..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                        </div>

                        <!-- Categorias -->
                        @if($categories->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Todas as categorias</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->slug }}" {{ ($filters['category'] ?? '') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Linha de Qualidade -->
                        @if($qualityLines->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Qualidade</label>
                            <select name="quality" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Todas as qualidades</option>
                                @foreach($qualityLines as $quality)
                                <option value="{{ $quality }}" {{ ($filters['quality'] ?? '') == $quality ? 'selected' : '' }}>
                                    {{ $quality }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Faixa de Preço -->
                        @if($priceRange)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Faixa de Preço</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" 
                                       name="min_price" 
                                       value="{{ $filters['min_price'] ?? '' }}"
                                       placeholder="Min: R$ {{ number_format($priceRange->min_price, 0) }}"
                                       min="{{ $priceRange->min_price }}"
                                       max="{{ $priceRange->max_price }}"
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                <input type="number" 
                                       name="max_price" 
                                       value="{{ $filters['max_price'] ?? '' }}"
                                       placeholder="Max: R$ {{ number_format($priceRange->max_price, 0) }}"
                                       min="{{ $priceRange->min_price }}"
                                       max="{{ $priceRange->max_price }}"
                                       class="px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                        @endif

                        <!-- Público Alvo -->
                        @if($audiences->count() > 0)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Público Alvo</label>
                            <select name="audience" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                                <option value="">Todos os públicos</option>
                                @foreach($audiences as $audience)
                                <option value="{{ $audience }}" {{ ($filters['audience'] ?? '') == $audience ? 'selected' : '' }}>
                                    {{ $audience }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Botões -->
                        <div class="space-y-2">
                            <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 transition duration-300">
                                Aplicar Filtros
                            </button>
                            <a href="{{ route('products.index') }}" class="w-full block text-center bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 transition duration-300">
                                Limpar Filtros
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de Produtos -->
            <div class="flex-1">
                <!-- Barra de Ordenação -->
                <div class="flex justify-between items-center mb-6 bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600">
                        Mostrando {{ $products->count() }} de {{ $products->total() }} produtos
                    </div>
                    
                    <form method="GET" action="{{ route('products.index') }}" class="flex items-center space-x-2">
                        <!-- Preservar filtros atuais -->
                        @foreach($filters as $key => $value)
                            @if($value && !in_array($key, ['sort', 'order']))
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <select name="sort" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <option value="name" {{ ($filters['sort'] ?? '') == 'name' ? 'selected' : '' }}>Nome A-Z</option>
                            <option value="price" {{ ($filters['sort'] ?? '') == 'price' ? 'selected' : '' }}>Preço</option>
                            <option value="created_at" {{ ($filters['sort'] ?? '') == 'created_at' ? 'selected' : '' }}>Mais Recentes</option>
                            <option value="featured" {{ ($filters['sort'] ?? '') == 'featured' ? 'selected' : '' }}>Destaques</option>
                        </select>
                        
                        <select name="order" onchange="this.form.submit()" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <option value="asc" {{ ($filters['order'] ?? '') == 'asc' ? 'selected' : '' }}>Crescente</option>
                            <option value="desc" {{ ($filters['order'] ?? '') == 'desc' ? 'selected' : '' }}>Decrescente</option>
                        </select>
                    </form>
                </div>

                <!-- Grid de Produtos -->
                @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($products as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300 group">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="aspect-square bg-gray-200 relative overflow-hidden">
                                @if($product->primary_image_url)
                                    <img src="{{ $product->primary_image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                                        <span class="text-6xl">👕</span>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="absolute top-2 left-2 space-y-1">
                                    @if($product->is_featured)
                                        <span class="block bg-purple-600 text-white px-2 py-1 text-xs rounded">Destaque</span>
                                    @endif
                                    @if($product->quality_line)
                                        <span class="block bg-blue-600 text-white px-2 py-1 text-xs rounded">{{ $product->quality_line }}</span>
                                    @endif
                                </div>

                                <!-- Botão rápido de adicionar ao carrinho -->
                                <div class="absolute bottom-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button onclick="openQuickAddModal({{ $product->id }})" 
                                            class="bg-purple-600 text-white p-2 rounded-full hover:bg-purple-700 transition duration-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13h10m-4 8a2 2 0 100-4 2 2 0 000 4zm-4 0a2 2 0 100-4 2 2 0 000 4z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </a>
                        
                        <div class="p-4">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg mb-1 hover:text-purple-600 line-clamp-2">
                                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    
                                    @if($product->category)
                                        <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ Str::limit($product->short_description, 80) }}</p>
                            
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-xl font-bold text-purple-600">{{ $product->formatted_price }}</span>
                                    @if($product->target_audience)
                                        <span class="block text-xs text-gray-500">{{ $product->target_audience }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', $product->slug) }}" 
                                   class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-300 text-sm">
                                    Ver Detalhes
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Paginação -->
                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
                @else
                <!-- Estado vazio -->
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum produto encontrado</h3>
                    <p class="text-gray-600 mb-6">Tente ajustar os filtros ou fazer uma nova busca</p>
                    <a href="{{ route('products.index') }}" 
                       class="bg-purple-600 text-white px-6 py-3 rounded-lg hover:bg-purple-700 transition duration-300">
                        Ver Todos os Produtos
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal de Adição Rápida (para implementar depois) -->
<div id="quick-add-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Adicionar ao Carrinho</h3>
            <p class="text-gray-600 mb-4">Funcionalidade em desenvolvimento...</p>
            <button onclick="closeQuickAddModal()" 
                    class="w-full bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition duration-300">
                Fechar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openQuickAddModal(productId) {
    // Por enquanto apenas mostra o modal
    document.getElementById('quick-add-modal').classList.remove('hidden');
}

function closeQuickAddModal() {
    document.getElementById('quick-add-modal').classList.add('hidden');
}

// Fechar modal clicando fora
document.getElementById('quick-add-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeQuickAddModal();
    }
});
</script>
@endpush
@endsection 