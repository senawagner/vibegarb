@extends('layouts.app')

@section('title', 'Carrinho de Compras - Vibe Garb')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Carrinho de Compras</h1>
            <p class="text-gray-600">{{ $itemCount }} {{ $itemCount == 1 ? 'item' : 'itens' }} no seu carrinho</p>
        </div>

        @if($itemCount > 0)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Lista de Itens -->
            <div class="lg:col-span-2 space-y-4">
                @foreach($cartItems as $item)
                <div class="bg-white rounded-lg shadow-md p-6" id="cart-item-{{ $item['key'] }}">
                    <div class="flex items-center space-x-4">
                        <!-- Imagem do Produto -->
                        <div class="w-20 h-20 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" 
                                     alt="{{ $item['name'] }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    <span class="text-2xl">👕</span>
                                </div>
                            @endif
                        </div>

                        <!-- Informações do Item -->
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                <a href="{{ $item['product_url'] }}" class="hover:text-purple-600">
                                    {{ $item['name'] }}
                                </a>
                            </h3>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                <span>Cor: {{ $item['color'] }}</span>
                                <span>Tamanho: {{ $item['size'] }}</span>
                                @if($item['quality_line'])
                                    <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded">{{ $item['quality_line'] }}</span>
                                @endif
                            </div>

                            <div class="flex items-center justify-between">
                                <!-- Controle de Quantidade -->
                                <div class="flex items-center space-x-2">
                                    <button onclick="updateQuantity('{{ $item['key'] }}', {{ $item['quantity'] - 1 }})" 
                                            class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition {{ $item['quantity'] <= 1 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item['quantity'] <= 1 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                        </svg>
                                    </button>
                                    
                                    <span class="w-12 text-center font-medium">{{ $item['quantity'] }}</span>
                                    
                                    <button onclick="updateQuantity('{{ $item['key'] }}', {{ $item['quantity'] + 1 }})" 
                                            class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center hover:bg-gray-300 transition {{ $item['quantity'] >= 10 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            {{ $item['quantity'] >= 10 ? 'disabled' : '' }}>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Preços -->
                                <div class="text-right">
                                    <div class="text-lg font-bold text-gray-900">{{ $item['formatted_total'] }}</div>
                                    <div class="text-sm text-gray-600">{{ $item['formatted_unit_price'] }} cada</div>
                                </div>
                            </div>
                        </div>

                        <!-- Botão Remover -->
                        <button onclick="removeItem('{{ $item['key'] }}')" 
                                class="text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach

                <!-- Ações do Carrinho -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('products.index') }}" 
                           class="flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Continuar Comprando
                        </a>
                        
                        <button onclick="clearCart()" 
                                class="flex items-center justify-center px-6 py-3 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Limpar Carrinho
                        </button>
                    </div>
                </div>
            </div>

            <!-- Resumo do Pedido -->
            <div class="space-y-6">
                <!-- Cálculos -->
                <div class="bg-white rounded-lg shadow-md p-6" id="cart-summary">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal ({{ $cartSummary['total_items'] }} itens)</span>
                            <span class="font-medium">{{ $cartSummary['formatted_subtotal'] }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frete</span>
                            <span class="font-medium">{{ $cartSummary['formatted_shipping'] }}</span>
                        </div>
                        
                        @if(!$cartSummary['has_free_shipping'])
                        <div class="text-sm text-gray-600 bg-blue-50 p-3 rounded-lg">
                            💡 Faltam {{ $cartSummary['formatted_free_shipping_remaining'] }} para frete grátis!
                        </div>
                        @else
                        <div class="text-sm text-green-600 bg-green-50 p-3 rounded-lg">
                            🎉 Parabéns! Você ganhou frete grátis!
                        </div>
                        @endif
                        
                        <div class="border-t pt-3">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total</span>
                                <span>{{ $cartSummary['formatted_total'] }}</span>
                            </div>
                        </div>
                        
                        <!-- Desconto PIX -->
                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                                <span class="font-semibold text-green-800">Pagamento via PIX</span>
                            </div>
                            <div class="text-sm text-green-700 mb-2">5% de desconto</div>
                            <div class="text-lg font-bold text-green-800">{{ $cartSummary['formatted_pix_total'] }}</div>
                        </div>
                    </div>
                </div>

                <!-- Opções de Frete -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Calcular Frete</h3>
                    
                    <form class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">CEP</label>
                            <div class="flex space-x-2">
                                <input type="text" 
                                       placeholder="00000-000" 
                                       maxlength="9"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                <button type="button" 
                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                    Calcular
                                </button>
                            </div>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            <p>📦 Frete grátis para compras acima de R$ 200,00</p>
                            <p>🚚 Entrega em 5-10 dias úteis</p>
                        </div>
                    </form>
                </div>

                <!-- Botão de Checkout -->
                <div class="space-y-3">
                    <a href="{{ route('checkout.index') }}" class="w-full bg-purple-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-300 flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Finalizar Compra
                    </a>
                    
                    <p class="text-xs text-gray-500 text-center">
                        Ao finalizar, você concorda com nossos termos de uso
                    </p>
                </div>

                <!-- Selo de Segurança -->
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            SSL Seguro
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Compra Protegida
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Carrinho Vazio -->
        <div class="text-center py-16">
            <div class="text-8xl mb-6">🛒</div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Seu carrinho está vazio</h2>
            <p class="text-gray-600 mb-8">Que tal dar uma olhada nos nossos produtos incríveis?</p>
            
            <div class="space-y-4">
                <a href="{{ route('products.index') }}" 
                   class="inline-flex items-center px-8 py-3 bg-purple-600 text-white rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-300">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Explorar Produtos
                </a>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-md mx-auto mt-8">
                    <a href="{{ route('products.index', ['category' => 'basicas']) }}" 
                       class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                        <div class="text-3xl mb-2">👕</div>
                        <div class="text-sm font-medium">Básicas</div>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'estampadas']) }}" 
                       class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                        <div class="text-3xl mb-2">🎨</div>
                        <div class="text-sm font-medium">Estampadas</div>
                    </a>
                    <a href="{{ route('products.index', ['category' => 'oversized']) }}" 
                       class="bg-white p-4 rounded-lg shadow hover:shadow-md transition">
                        <div class="text-3xl mb-2">📏</div>
                        <div class="text-sm font-medium">Oversized</div>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function updateQuantity(itemKey, newQuantity) {
    if (newQuantity < 1 || newQuantity > 10) return;
    
    fetch(`/carrinho/atualizar/${itemKey}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Recarregar para atualizar os cálculos
        } else {
            alert('Erro ao atualizar quantidade');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao atualizar quantidade');
    });
}

function removeItem(itemKey) {
    if (!confirm('Tem certeza que deseja remover este item?')) return;
    
    fetch(`/carrinho/remover/${itemKey}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao remover item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao remover item');
    });
}

function clearCart() {
    if (!confirm('Tem certeza que deseja limpar todo o carrinho?')) return;
    
    fetch('/carrinho/limpar', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Erro ao limpar carrinho');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erro ao limpar carrinho');
    });
}

// Formatação de CEP
document.querySelector('input[placeholder="00000-000"]').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = value.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = value;
});
</script>
@endpush
@endsection 