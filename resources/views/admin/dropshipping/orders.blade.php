@extends('admin.layouts.app')

@section('title', 'Pedidos Dropshipping')
@section('page-title', 'Pedidos Dropshipping - {{ ucfirst($status) }}')

@section('content')
<div class="p-6">
    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Filtrar por Status</h3>
        </div>
        <div class="px-6 py-4">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.dropshipping.ready_for_supplier') }}" 
                   class="px-4 py-2 rounded-md {{ $status === 'ready_for_supplier' ? 'bg-yellow-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Pendentes ({{ $counts['ready_for_supplier'] ?? 0 }})
                </a>
                <a href="{{ route('admin.dropshipping.in_production') }}" 
                   class="px-4 py-2 rounded-md {{ $status === 'in_production' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Em Produção ({{ $counts['in_production'] ?? 0 }})
                </a>
                <a href="{{ route('admin.dropshipping.shipped_by_supplier') }}" 
                   class="px-4 py-2 rounded-md {{ $status === 'shipped_by_supplier' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Enviados ({{ $counts['shipped_by_supplier'] ?? 0 }})
                </a>
            </div>
        </div>
    </div>

    <!-- Lista de Pedidos -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">
                    Pedidos {{ ucfirst(str_replace('_', ' ', $status)) }}
                </h3>
                @if($status === 'ready_for_supplier' && count($orders) > 0)
                <form method="POST" action="{{ route('admin.dropshipping.export_for_supplier') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar para Fábrica
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedido</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produtos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Custo Produção</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lucro</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                            <div class="text-sm text-gray-500">{{ $order->items_count }} itens</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->customer_email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                @foreach($order->items as $item)
                                    <div class="mb-1">
                                        {{ $item->product->name }} ({{ $item->quantity }}x)
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($order->total_amount, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            R$ {{ number_format($order->production_cost, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-green-600">
                                R$ {{ number_format($order->total_amount - $order->production_cost, 2, ',', '.') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.dropshipping.show_order', $order->id) }}" 
                               class="text-indigo-600 hover:text-indigo-900 mr-3">Ver Detalhes</a>
                            
                            @if($status === 'ready_for_supplier')
                            <form method="POST" action="{{ route('admin.dropshipping.confirm_supplier', $order->id) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-green-600 hover:text-green-900">Confirmar</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            Nenhum pedido encontrado com este status
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