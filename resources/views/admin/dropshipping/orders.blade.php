@extends('admin.layouts.app')

@section('title', 'Pedidos Dropshipping')
@section('page-title', 'Todos os Pedidos Pagos')

@section('content')
<div class="p-6">
    <!-- Lista de Pedidos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">
                Pedidos Pagos Aguardando Processamento
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($order->supplier_status == 'pending') bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->supplier_status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.dropshipping.show_order', $order->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900 mr-3">Ver Detalhes</a>
                            
                            {{-- O botão só aparece se o pedido foi pago e ainda não foi enviado ao fornecedor --}}
                            @if($order->status === 'paid' && is_null($order->sent_to_supplier_at))
                                <form action="{{ route('admin.dropshipping.send_to_supplier', $order) }}" method="POST" class="inline-block" onsubmit="return confirm('Tem certeza que deseja enviar este pedido para produção na Dimona? Esta ação não pode ser desfeita.');">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 font-semibold">
                                        Enviar para Produção
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-gray-500 italic">Já processado</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Nenhum pedido pago encontrado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection 