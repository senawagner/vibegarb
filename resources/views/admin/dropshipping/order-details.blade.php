@extends('admin.layouts.app')

@section('title', 'Detalhes do Pedido #' . $order->id)
@section('page-title', 'Pedido #' . $order->id)

@section('content')
<div class="p-6">
    <!-- Informações do Pedido -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Informações Básicas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informações do Pedido</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Status de Produção</p>
                            <p class="text-sm text-gray-900">
                                @switch($order->production_status)
                                    @case('pending')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pendente
                                        </span>
                                        @break
                                    @case('confirmed')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Confirmado com Fábrica
                                        </span>
                                        @break
                                    @case('in_production')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Em Produção
                                        </span>
                                        @break
                                    @case('ready')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Pronto para Envio
                                        </span>
                                        @break
                                    @case('shipped')
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                            Enviado pela Fábrica
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($order->production_status) }}
                                        </span>
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Data do Pedido</p>
                            <p class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Valor Total</p>
                            <p class="text-sm font-semibold text-gray-900">R$ {{ number_format($order->total_amount, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Custo de Produção</p>
                            <p class="text-sm text-gray-900">R$ {{ number_format($order->production_cost, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Lucro Estimado</p>
                            <p class="text-sm font-semibold text-green-600">R$ {{ number_format($order->total_amount - $order->production_cost, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Prazo de Produção</p>
                            <p class="text-sm text-gray-900">{{ $order->production_days ?? 'N/A' }} dias</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Ações</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if($order->production_status === 'pending')
                    <form method="POST" action="{{ route('admin.dropshipping.confirm_supplier', $order->id) }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Confirmar com Fábrica
                        </button>
                    </form>
                    @endif

                    @if($order->production_status === 'confirmed')
                    <form method="POST" action="{{ route('admin.dropshipping.mark_paid', $order->id) }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            Marcar como Pago
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.dropshipping.dashboard') }}" 
                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar ao Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações do Cliente -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informações do Cliente</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nome</p>
                    <p class="text-sm text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="text-sm text-gray-900">{{ $order->customer_email }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Telefone</p>
                    <p class="text-sm text-gray-900">{{ $order->customer_phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">CPF/CNPJ</p>
                    <p class="text-sm text-gray-900">{{ $order->customer_document ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Endereço de Entrega -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Endereço de Entrega</h3>
        </div>
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">CEP</p>
                    <p class="text-sm text-gray-900">{{ $order->shipping_zipcode }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Endereço</p>
                    <p class="text-sm text-gray-900">{{ $order->shipping_address }}, {{ $order->shipping_number }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Complemento</p>
                    <p class="text-sm text-gray-900">{{ $order->shipping_complement ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Bairro</p>
                    <p class="text-sm text-gray-900">{{ $order->shipping_neighborhood }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Cidade/UF</p>
                    <p class="text-sm text-gray-900">{{ $order->shipping_city }}/{{ $order->shipping_state }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Frete</p>
                    <p class="text-sm text-gray-900">R$ {{ number_format($order->shipping_cost, 2, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Produtos do Pedido -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Produtos do Pedido</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantidade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preço Unit.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Custo Produção</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $item->product->name }}</div>
                            <div class="text-sm text-gray-500">{{ $item->product->sku ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->quantity }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($item->total_price, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($item->production_cost, 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Notas e Observações -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Notas Internas -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Notas Internas</h3>
            </div>
            <div class="px-6 py-4">
                <form method="POST" action="{{ route('admin.dropshipping.update_internal_notes', $order->id) }}">
                    @csrf
                    <textarea name="internal_notes" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $order->internal_notes }}</textarea>
                    <button type="submit" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        Salvar Notas
                    </button>
                </form>
            </div>
        </div>

        <!-- Notas da Fábrica -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Notas da Fábrica</h3>
            </div>
            <div class="px-6 py-4">
                <form method="POST" action="{{ route('admin.dropshipping.update_supplier_notes', $order->id) }}">
                    @csrf
                    <textarea name="supplier_notes" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $order->supplier_notes }}</textarea>
                    <button type="submit" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        Salvar Notas
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tracking -->
    @if($order->production_status === 'shipped')
    <div class="bg-white rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informações de Tracking</h3>
        </div>
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('admin.dropshipping.update_tracking', $order->id) }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Código de Rastreio</label>
                        <input type="text" name="tracking_code" value="{{ $order->tracking_code }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">URL de Rastreio</label>
                        <input type="url" name="tracking_url" value="{{ $order->tracking_url }}" 
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <button type="submit" class="mt-4 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">
                    Atualizar Tracking
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection 