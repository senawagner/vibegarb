@extends('layouts.app')

@section('title', $product->name . ' - Vibe Garb')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600">
                        Início
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('products.index') }}" class="ml-1 text-gray-700 hover:text-purple-600 md:ml-2">
                            Produtos
                        </a>
                    </div>
                </li>
                @if($product->category)
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="ml-1 text-gray-700 hover:text-purple-600 md:ml-2">
                            {{ $product->category->name }}
                        </a>
                    </div>
                </li>
                @endif
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-500 md:ml-2">{{ Str::limit($product->name, 30) }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Galeria de Imagens -->
            <div class="space-y-4">
                <!-- Imagem Principal -->
                <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden">
                    @if($product->primary_image_url)
                        <img id="main-image" 
                             src="{{ $product->primary_image_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                            <span class="text-8xl">👕</span>
                        </div>
                    @endif
                </div>

                <!-- Miniaturas -->
                @if($product->productImages->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->productImages as $image)
                    <button onclick="changeMainImage('{{ $image->image_url }}')" 
                            class="aspect-square bg-gray-200 rounded-lg overflow-hidden hover:ring-2 hover:ring-purple-500 transition">
                        <img src="{{ $image->image_url }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Informações do Produto -->
            <div class="space-y-6">
                <div>
                    <!-- Badges -->
                    <div class="flex space-x-2 mb-3">
                        @if($product->is_featured)
                            <span class="bg-purple-600 text-white px-3 py-1 text-sm rounded-full">Destaque</span>
                        @endif
                        @if($product->quality_line)
                            <span class="bg-blue-600 text-white px-3 py-1 text-sm rounded-full">{{ $product->quality_line }}</span>
                        @endif
                        @if($product->category)
                            <span class="bg-gray-600 text-white px-3 py-1 text-sm rounded-full">{{ $product->category->name }}</span>
                        @endif
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                    
                    <!-- Preço -->
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-purple-600">{{ $product->formatted_price }}</span>
                        @if($product->quality_line)
                            <p class="text-gray-600 mt-1">{{ $product->quality_description ?? $product->quality_line }}</p>
                        @endif
                    </div>

                    <!-- Descrição Curta -->
                    @if($product->short_description)
                    <p class="text-lg text-gray-700 mb-6">{{ $product->short_description }}</p>
                    @endif
                </div>

                <!-- Formulário de Adição ao Carrinho -->
                <form action="{{ route('cart.add') }}" method="POST" id="add-to-cart-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="space-y-6">
                        <!-- Seleção de Cor -->
                        @if(count($availableColors) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Cor</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($availableColors as $color)
                                <label class="cursor-pointer">
                                    <input type="radio" 
                                           name="color" 
                                           value="{{ $color }}" 
                                           class="sr-only peer" 
                                           required>
                                    <div class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-purple-400 transition">
                                        {{ $color }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Seleção de Tamanho -->
                        @if(count($availableSizes) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Tamanho</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($availableSizes as $size)
                                <label class="cursor-pointer">
                                    <input type="radio" 
                                           name="size" 
                                           value="{{ $size }}" 
                                           class="sr-only peer" 
                                           required>
                                    <div class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-purple-400 transition text-center min-w-[50px]">
                                        {{ $size }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                            
                            <!-- Guia de Tamanhos -->
                            <button type="button" 
                                    onclick="openSizeGuide()" 
                                    class="text-sm text-purple-600 hover:text-purple-800 mt-2">
                                📏 Guia de Tamanhos
                            </button>
                        </div>
                        @endif

                        <!-- Quantidade -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Quantidade</label>
                            <div class="flex items-center space-x-3">
                                <button type="button" 
                                        onclick="decreaseQuantity()" 
                                        class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity" 
                                       value="1" 
                                       min="1" 
                                       max="10" 
                                       class="w-20 text-center border border-gray-300 rounded-lg py-2">
                                <button type="button" 
                                        onclick="increaseQuantity()" 
                                        class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="space-y-3">
                            <button type="submit" 
                                    class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-purple-700 transition duration-300 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13h10m-4 8a2 2 0 100-4 2 2 0 000 4zm-4 0a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                                Adicionar ao Carrinho
                            </button>
                            
                            <button type="button" 
                                    class="w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Adicionar aos Favoritos
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Informações Adicionais -->
                <div class="border-t pt-6">
                    <div class="space-y-4">
                        @if($product->target_audience)
                        <div class="flex items-center">
                            <span class="font-medium text-gray-700 w-32">Público:</span>
                            <span class="text-gray-600">{{ $product->target_audience }}</span>
                        </div>
                        @endif
                        
                        @if($product->sku)
                        <div class="flex items-center">
                            <span class="font-medium text-gray-700 w-32">SKU:</span>
                            <span class="text-gray-600">{{ $product->sku }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center">
                            <span class="font-medium text-gray-700 w-32">Estoque:</span>
                            <span class="text-green-600">{{ $product->isInStock() ? 'Disponível' : 'Indisponível' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Descrição Completa -->
        @if($product->description)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Descrição do Produto</h2>
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($product->description)) !!}
            </div>
        </div>
        @endif

        <!-- Produtos Relacionados -->
        @if($relatedProducts->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Produtos Relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                    <a href="{{ route('products.show', $related->slug) }}">
                        <div class="aspect-square bg-gray-200 relative overflow-hidden">
                            @if($related->primary_image_url)
                                <img src="{{ $related->primary_image_url }}" 
                                     alt="{{ $related->name }}" 
                                     class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    <span class="text-4xl">👕</span>
                                </div>
                            @endif
                        </div>
                    </a>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 hover:text-purple-600">
                            <a href="{{ route('products.show', $related->slug) }}">{{ Str::limit($related->name, 40) }}</a>
                        </h3>
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-bold text-purple-600">{{ $related->formatted_price }}</span>
                            <a href="{{ route('products.show', $related->slug) }}" 
                               class="bg-purple-600 text-white px-3 py-1 rounded text-sm hover:bg-purple-700 transition duration-300">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Modal do Guia de Tamanhos -->
<div id="size-guide-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full max-h-[80vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Guia de Tamanhos</h3>
                <button onclick="closeSizeGuide()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-2">Tamanho</th>
                            <th class="text-left py-2">Peito (cm)</th>
                            <th class="text-left py-2">Comprimento (cm)</th>
                            <th class="text-left py-2">Ombro (cm)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="py-2 font-medium">PP</td>
                            <td class="py-2">88-92</td>
                            <td class="py-2">68</td>
                            <td class="py-2">42</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium">P</td>
                            <td class="py-2">92-96</td>
                            <td class="py-2">70</td>
                            <td class="py-2">44</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium">M</td>
                            <td class="py-2">96-100</td>
                            <td class="py-2">72</td>
                            <td class="py-2">46</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium">G</td>
                            <td class="py-2">100-104</td>
                            <td class="py-2">74</td>
                            <td class="py-2">48</td>
                        </tr>
                        <tr class="border-b">
                            <td class="py-2 font-medium">GG</td>
                            <td class="py-2">104-108</td>
                            <td class="py-2">76</td>
                            <td class="py-2">50</td>
                        </tr>
                    </tbody>
                </table>
                
                <p class="text-sm text-gray-600">
                    💡 <strong>Dica:</strong> Medidas podem variar ±2cm dependendo do modelo e tecido.
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeMainImage(imageUrl) {
    document.getElementById('main-image').src = imageUrl;
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue < 10) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}

function openSizeGuide() {
    document.getElementById('size-guide-modal').classList.remove('hidden');
}

function closeSizeGuide() {
    document.getElementById('size-guide-modal').classList.add('hidden');
}

// Fechar modal clicando fora
document.getElementById('size-guide-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSizeGuide();
    }
});

// Validação do formulário
document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
    const colorSelected = document.querySelector('input[name="color"]:checked');
    const sizeSelected = document.querySelector('input[name="size"]:checked');
    
    let errors = [];
    
    @if(count($availableColors) > 0)
    if (!colorSelected) {
        errors.push('Selecione uma cor');
    }
    @endif
    
    @if(count($availableSizes) > 0)
    if (!sizeSelected) {
        errors.push('Selecione um tamanho');
    }
    @endif
    
    if (errors.length > 0) {
        e.preventDefault();
        alert('Por favor:\n' + errors.join('\n'));
    }
});
</script>
@endpush
@endsection 