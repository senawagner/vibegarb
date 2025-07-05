<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DropshippingController extends Controller
{
    /**
     * Dashboard de dropshipping
     */
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('payment_status', 'completed')->where('supplier_status', 'pending')->count(),
            'in_production' => Order::where('production_status', 'in_production')->count(),
            'ready_to_ship' => Order::where('production_status', 'ready_to_ship')->count(),
            'total_revenue' => Order::where('payment_status', 'completed')->sum('total_amount'),
            'total_profit' => Order::where('payment_status', 'completed')->sum('profit_margin'),
            'unpaid_to_supplier' => Order::where('supplier_paid', false)->where('supplier_status', 'confirmed')->count(),
        ];

        $recentOrders = Order::with(['user', 'items'])
            ->where('payment_status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dropshipping.dashboard', compact('stats', 'recentOrders'));
    }

    /**
     * Lista de pedidos prontos para enviar à fábrica
     */
    public function readyForSupplier()
    {
        $orders = Order::with(['user', 'items.product'])
            ->where('payment_status', 'completed')
            ->where('supplier_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.dropshipping.ready-for-supplier', compact('orders'));
    }

    /**
     * Lista de pedidos em produção
     */
    public function inProduction()
    {
        $orders = Order::with(['user', 'items.product'])
            ->where('production_status', 'in_production')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.dropshipping.in-production', compact('orders'));
    }

    /**
     * Lista de pedidos enviados pela fábrica
     */
    public function shippedBySupplier()
    {
        $orders = Order::with(['user', 'items.product'])
            ->where('supplier_status', 'shipped')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.dropshipping.shipped-by-supplier', compact('orders'));
    }

    /**
     * Detalhes de um pedido específico
     */
    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'items.product.images']);
        
        return view('admin.dropshipping.show', compact('order'));
    }

    /**
     * Confirmar pedido com a fábrica
     */
    public function confirmWithSupplier(Order $order)
    {
        $order->update([
            'supplier_status' => 'confirmed',
            'production_status' => 'confirmed',
            'estimated_production_date' => now()->addDays($order->production_days),
            'estimated_shipping_date' => now()->addDays($order->production_days + 3),
            'estimated_delivery_date' => now()->addDays($order->production_days + 8),
        ]);

        return back()->with('success', 'Pedido confirmado com a fábrica!');
    }

    /**
     * Marcar como pago à fábrica
     */
    public function markAsPaidToSupplier(Request $request, Order $order)
    {
        $request->validate([
            'payment_amount' => 'required|numeric|min:0'
        ]);

        $order->update([
            'supplier_paid' => true,
            'supplier_payment_date' => now(),
            'supplier_payment_amount' => $request->payment_amount
        ]);

        return back()->with('success', 'Pagamento à fábrica registrado!');
    }

    /**
     * Atualizar status de produção
     */
    public function updateProductionStatus(Request $request, Order $order)
    {
        $request->validate([
            'production_status' => 'required|in:confirmed,in_production,ready_to_ship,shipped'
        ]);

        $order->update([
            'production_status' => $request->production_status
        ]);

        if ($request->production_status === 'shipped') {
            $order->update([
                'supplier_status' => 'shipped'
            ]);
        }

        return back()->with('success', 'Status de produção atualizado!');
    }

    /**
     * Atualizar código de rastreio da fábrica
     */
    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'supplier_tracking_code' => 'required|string|max:100',
            'supplier_tracking_url' => 'nullable|url'
        ]);

        $order->update([
            'supplier_tracking_code' => $request->supplier_tracking_code,
            'supplier_tracking_url' => $request->supplier_tracking_url,
            'supplier_status' => 'shipped',
            'production_status' => 'shipped'
        ]);

        return back()->with('success', 'Código de rastreio atualizado!');
    }

    /**
     * Adicionar observações para a fábrica
     */
    public function updateSupplierNotes(Request $request, Order $order)
    {
        $request->validate([
            'supplier_notes' => 'required|string|max:1000'
        ]);

        $order->update([
            'supplier_notes' => $request->supplier_notes
        ]);

        return back()->with('success', 'Observações para a fábrica atualizadas!');
    }

    /**
     * Adicionar observações internas
     */
    public function updateInternalNotes(Request $request, Order $order)
    {
        $request->validate([
            'internal_notes' => 'required|string|max:1000'
        ]);

        $order->update([
            'internal_notes' => $request->internal_notes
        ]);

        return back()->with('success', 'Observações internas atualizadas!');
    }

    /**
     * Gerar relatório financeiro
     */
    public function financialReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        $orders = Order::where('payment_status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $report = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'total_production_cost' => $orders->sum('production_cost'),
            'total_supplier_shipping' => $orders->sum('supplier_shipping_cost'),
            'total_profit' => $orders->sum('profit_margin'),
            'average_profit_margin' => $orders->avg('profit_margin'),
            'orders_by_status' => $orders->groupBy('supplier_status')->map->count(),
        ];

        return view('admin.dropshipping.financial-report', compact('report', 'startDate', 'endDate'));
    }

    /**
     * Exportar pedidos para fábrica
     */
    public function exportForSupplier(Request $request)
    {
        $orderIds = $request->get('order_ids', []);
        
        $orders = Order::with(['items.product', 'items.product.supplier'])
            ->whereIn('id', $orderIds)
            ->where('payment_status', 'completed')
            ->where('supplier_status', 'pending')
            ->get();

        // Aqui você pode gerar um arquivo CSV ou JSON para enviar à fábrica
        $exportData = [];
        
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $exportData[] = [
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'customer_address' => $order->shipping_address_line . ', ' . $order->shipping_number,
                    'customer_city' => $order->shipping_city,
                    'customer_state' => $order->shipping_state,
                    'customer_zipcode' => $order->shipping_zipcode,
                    'product_name' => $item->product_name,
                    'product_color' => $item->product_color,
                    'product_size' => $item->product_size,
                    'quantity' => $item->quantity,
                    'notes' => $order->supplier_notes,
                ];
            }
        }

        return response()->json($exportData);
    }
}
