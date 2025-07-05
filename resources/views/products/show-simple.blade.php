@extends('layouts.app')

@section('title', $product->name . ' - Vibe Garb')

@section('content')
<div class="bg-white py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li><a href="{{ route('home') }}" class="text-gray-700 hover:text-purple-600">Início</a></li>
                <li><span class="text-gray-400 mx-2">></span></li>
                <li><a href="{{ route('products.index') }}" class="text-gray-700 hover:text-purple-600">Produtos</a></li>
                <li><span class="text-gray-400 mx-2">></span></li>
                <li><span class="text-gray-500">{{ $product->name }}</span></li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            
            <!-- Galeria de Imagens -->
            <div class="space-y-4">
                <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden flex items-center justify-center">
                    <span class="text-8xl">👕</span>
                </div>
                <p class="text-sm text-gray-500 text-center">Imagem ilustrativa</p>
            </div>

            <!-- Informações do Produto -->
            <div class="space-y-6">
                
                <!-- Badges -->
                <div class="flex space-x-2">
                    @if($product->is_featured)
                        <span class="bg-purple-600 text-white px-3 py-1 text-sm rounded-full">✨ Destaque</span>
                    @endif
                    @if($product->quality_line)
                        <span class="bg-blue-600 text-white px-3 py-1 text-sm rounded-full">{{ $product->quality_line }}</span>
                    @endif
                </div>

                <!-- Nome e Preço -->
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $product->name }}</h1>
                    <div class="mb-4">
                        <span class="text-4xl font-bold text-purple-600">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                        @if($product->quality_line)
                            <p class="text-gray-600 mt-2">{{ $product->quality_line }} • Qualidade Premium</p>
                        @endif
                    </div>
                    
                    @if($product->short_description)
                        <p class="text-lg text-gray-700 mb-6">{{ $product->short_description }}</p>
                    @endif
                </div>

                <!-- Formulário -->
                <form action="{{ route('cart.add') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <!-- Seleção de Cor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Cor</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableColors as $color)
                            <label class="cursor-pointer">
                                <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" required>
                                <div class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-purple-400 transition">
                                    {{ $color }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Seleção de Tamanho -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Tamanho</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($availableSizes as $size)
                            <label class="cursor-pointer">
                                <input type="radio" name="size" value="{{ $size }}" class="sr-only peer" required>
                                <div class="px-4 py-2 border-2 border-gray-300 rounded-lg peer-checked:border-purple-600 peer-checked:bg-purple-50 hover:border-purple-400 transition text-center min-w-[50px]">
                                    {{ $size }}
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Quantidade -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Quantidade</label>
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="decreaseQty()" class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300">
                                -
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="w-20 text-center border border-gray-300 rounded-lg py-2">
                            <button type="button" onclick="increaseQty()" class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300">
                                +
                            </button>
                        </div>
                    </div>

                    <!-- Botões -->
                    <div class="space-y-3">
                        <button type="submit" class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-purple-700 transition duration-300">
                            🛒 Adicionar ao Carrinho
                        </button>
                        <button type="button" class="w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-lg font-semibold hover:bg-gray-300 transition duration-300">
                            ❤️ Favoritar
                        </button>
                    </div>
                </form>

                <!-- Informações Extra -->
                <div class="border-t pt-6 space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">SKU:</span>
                        <span class="font-medium">{{ $product->sku ?? 'VG-' . $product->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Estoque:</span>
                        <span class="text-green-600 font-medium">✅ Disponível</span>
                    </div>
                    @if($product->target_audience)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Público:</span>
                        <span class="font-medium">{{ $product->target_audience }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Descrição -->
        @if($product->description)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Descrição</h2>
            <div class="bg-gray-50 rounded-lg p-6">
                <p class="text-gray-700">{{ $product->description }}</p>
            </div>
        </div>
        @endif

        <!-- Produtos Relacionados Placeholder -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Você também pode gostar</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @for($i = 1; $i <= 4; $i++)
                <div class="bg-gray-100 rounded-lg p-6 text-center">
                    <div class="text-4xl mb-4">👕</div>
                    <h3 class="font-semibold mb-2">Produto Relacionado {{ $i }}</h3>
                    <p class="text-purple-600 font-bold">R$ 39,90</p>
                    <button class="mt-3 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">
                        Ver
                    </button>
                </div>
                @endfor
            </div>
        </div>
    </div>
</div>

<script>
function increaseQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current < 10) {
        input.value = current + 1;
    }
}

function decreaseQty() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const color = document.querySelector('input[name="color"]:checked');
    const size = document.querySelector('input[name="size"]:checked');
    
    if (!color || !size) {
        e.preventDefault();
        alert('Por favor, selecione cor e tamanho!');
    }
});
</script>
@endsection 