@extends('layouts.app')

@section('title', 'Pedido Confirmado - Vibe Garb')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        
        <!-- Sucesso -->
        <div class="text-center mb-8">
            <div class="mb-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Pedido Confirmado! 🎉</h1>
                <p class="text-lg text-gray-600">Obrigado por comprar na Vibe Garb!</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Detalhes do Pedido -->
            <div class="space-y-6">
                <!-- Número do Pedido -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes do Pedido</h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Número do Pedido:</span>
                            <span class="font-bold text-purple-600">{{ $order->order_number }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Data do Pedido:</span>
                            <span class="font-medium">{{ $order->created_at->format('d/m/Y \à\s H:i') }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Status:</span>
                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded">Pendente</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Forma de Pagamento:</span>
                            <span class="font-medium">
                                @if($order->payment_method === 'pix')
                                    PIX (5% OFF)
                                @elseif($order->payment_method === 'boleto')
                                    Boleto Bancário
                                @else
                                    Cartão de Crédito
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Itens do Pedido -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Itens do Pedido</h2>
                    
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                        <div class="flex items-center space-x-3 pb-3 border-b border-gray-100 last:border-b-0">
                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-2xl flex-shrink-0">👕</div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-600">{{ $item->product_color }} • {{ $item->product_size }} • Qtd: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-sm font-medium text-gray-900">
                                R$ {{ number_format($item->total_price, 2, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Totais -->
                    <div class="border-t pt-4 mt-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>R$ {{ number_format($order->subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Frete:</span>
                            <span>
                                @if($order->shipping_cost > 0)
                                    R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}
                                @else
                                    Grátis
                                @endif
                            </span>
                        </div>
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Desconto PIX:</span>
                            <span>-R$ {{ number_format($order->discount_amount, 2, ',', '.') }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between text-lg font-bold border-t pt-2">
                            <span>Total:</span>
                            <span>R$ {{ number_format($order->total_amount, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Endereço e Ações -->
            <div class="space-y-6">
                <!-- Endereço de Entrega -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Endereço de Entrega</h2>
                    
                    <div class="text-sm text-gray-700 space-y-1">
                        <p class="font-medium">{{ $order->customer_name }}</p>
                        <p>{{ $order->shipping_address_line }}, {{ $order->shipping_number }}</p>
                        @if($order->shipping_complement)
                            <p>{{ $order->shipping_complement }}</p>
                        @endif
                        <p>{{ $order->shipping_neighborhood }}</p>
                        <p>{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                        <p>CEP: {{ $order->shipping_zipcode }}</p>
                        <p class="mt-2 pt-2 border-t text-gray-600">
                            📧 {{ $order->customer_email }}<br>
                            📱 {{ $order->customer_phone }}
                        </p>
                    </div>
                </div>

                <!-- Ações -->
                <div class="text-center space-y-4">
                    <a href="{{ route('products.index') }}" 
                       class="block w-full bg-purple-600 text-white py-3 px-6 rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-300">
                        Continuar Comprando
                    </a>
                    
                    <a href="{{ route('home') }}" class="block text-purple-600 hover:text-purple-800 text-sm">
                        Voltar ao Início
                    </a>
                </div>
            </div>
        </div>

        <!-- Contato -->
        <div class="mt-12 p-6 bg-blue-50 rounded-lg text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Precisa de Ajuda?</h3>
            <p class="text-sm text-gray-600 mb-4">
                Entre em contato conosco se tiver alguma dúvida sobre seu pedido.
            </p>
            <div class="flex items-center justify-center space-x-6 text-sm">
                <a href="mailto:contato@vibegarb.com" class="text-purple-600 hover:text-purple-800">
                    📧 contato@vibegarb.com
                </a>
                <a href="tel:+5511999999999" class="text-purple-600 hover:text-purple-800">
                    📱 (11) 99999-9999
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 