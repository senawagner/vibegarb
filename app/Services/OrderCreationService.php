<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;

class OrderCreationService
{
    /**
     * Cria um novo pedido com base nos dados do carrinho e da requisição.
     *
     * @param array $validatedData Dados validados do formulário de checkout.
     * @param array $cart Carrinho de compras da sessão.
     * @param array $cartSummary Sumário calculado do carrinho (totais, frete, etc.).
     * @return Order O pedido que foi criado.
     * @throws Exception Se a validação interna falhar ou ocorrer um erro.
     */
    public function createOrder(array $validatedData, array $cart, array $cartSummary): Order
    {
        // Determinar valor final baseado no método de pagamento
        $finalTotal = $validatedData['payment_method'] === 'pix' 
            ? $cartSummary['pix_total'] 
            : $cartSummary['total'];

        // Calcular custos de produção (dropshipping)
        $productionCost = 0;
        $maxProductionDays = 0;
        
        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if ($product) {
                $productionCost += ($product->cost_price ?? 0) * $item['quantity'];
                $maxProductionDays = max($maxProductionDays, $product->production_days ?? 5);
            }
        }
        
        // Calcular margem de lucro
        $profitMargin = $finalTotal - $productionCost - $cartSummary['shipping_cost'];
        
        // Construir os dados do pedido
        $orderData = [
            'user_id' => Auth::id(),
            'order_number' => 'VG' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
            'status' => 'paid', // Simula pagamento aprovado para dropshipping
            'payment_method' => $validatedData['payment_method'],
            'payment_status' => 'completed',
            'subtotal' => $cartSummary['subtotal'],
            'shipping_cost' => $cartSummary['shipping_cost'],
            'discount_amount' => $validatedData['payment_method'] === 'pix' ? $cartSummary['pix_discount'] : 0,
            'total_amount' => $finalTotal,
            'customer_name' => $validatedData['customer_name'],
            'customer_email' => $validatedData['customer_email'],
            'customer_phone' => $validatedData['customer_phone'],
            'shipping_zipcode' => $validatedData['zipcode'],
            'shipping_address_line' => $validatedData['address'],
            'shipping_number' => $validatedData['number'],
            'shipping_complement' => $validatedData['complement'],
            'shipping_neighborhood' => $validatedData['neighborhood'],
            'shipping_city' => $validatedData['city'],
            'shipping_state' => $validatedData['state'],
            'shipping_address' => [
                'zipcode' => $validatedData['zipcode'],
                'address' => $validatedData['address'],
                'number' => $validatedData['number'],
                'complement' => $validatedData['complement'],
                'neighborhood' => $validatedData['neighborhood'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
            ],
            'billing_address' => [ // Assumindo o mesmo endereço para simplificar
                'zipcode' => $validatedData['zipcode'],
                'address' => $validatedData['address'],
                'number' => $validatedData['number'],
                'complement' => $validatedData['complement'],
                'neighborhood' => $validatedData['neighborhood'],
                'city' => $validatedData['city'],
                'state' => $validatedData['state'],
            ],
            'production_cost' => $productionCost,
            'supplier_shipping_cost' => $cartSummary['shipping_cost'],
            'profit_margin' => $profitMargin,
            'production_days' => $maxProductionDays,
            'production_status' => 'pending',
            'supplier_status' => 'pending',
        ];

        // Validar dados internos antes de criar o pedido
        $this->validateInternalData($orderData);

        // Criar o pedido
        $order = Order::create($orderData);

        // Criar os itens do pedido
        $this->createOrderItems($order, $cart);
        
        // Retornar o pedido criado
        return $order;
    }

    /**
     * Valida os dados internos do pedido antes da criação.
     *
     * @param array $orderData
     * @throws Exception
     */
    private function validateInternalData(array $orderData): void
    {
        $validator = Validator::make($orderData, [
            'status' => ['required', Rule::in(Order::getStatuses())],
            'payment_status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded'])]
        ]);

        if ($validator->fails()) {
            Log::error('Validação interna de dados do pedido falhou.', [
                'errors' => $validator->errors(),
                'data' => $orderData
            ]);
            throw new Exception('Erro interno do servidor: Dados de pedido inválidos.');
        }
    }

    /**
     * Cria os itens do pedido no banco de dados.
     *
     * @param Order $order
     * @param array $cart
     */
    private function createOrderItems(Order $order, array $cart): void
    {
        foreach ($cart as $item) {
            $sku = null;
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::find($item['variant_id']);
                $sku = $variant->sku ?? null;
            }
            
            if (is_null($sku)) {
                $product = Product::find($item['product_id']);
                $sku = $product->sku ?? 'N/A';
            }

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'product_sku' => $sku,
                'product_name' => $item['name'],
                'product_color' => $item['color'],
                'product_size' => $item['size'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }
    }
} 