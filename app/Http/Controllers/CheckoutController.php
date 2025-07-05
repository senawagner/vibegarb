<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Exibir página de checkout
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }
        
        // Calcular resumo do carrinho
        $cartSummary = $this->calculateCartSummary($cart);
        $cartItems = $this->getCartItems($cart);
        
        // Se usuário está logado, pré-preencher dados
        $userData = null;
        if (Auth::check()) {
            $user = Auth::user();
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
                'address' => $user->address ?? '',
                'city' => $user->city ?? '',
                'state' => $user->state ?? '',
                'zipcode' => $user->zip_code ?? '',
            ];
        }
        
        return view('checkout.index', compact('cartItems', 'cartSummary', 'userData'));
    }
    
    /**
     * Processar pedido
     */
    public function process(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:pix,boleto,card',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string|min:10|max:15',
            'zipcode' => 'required|string|min:8|max:9',
            'address' => 'required|string|max:255',
            'number' => 'required|string|max:10',
            'complement' => 'nullable|string|max:100',
            'neighborhood' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state' => 'required|string|size:2'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Carrinho vazio!');
        }
        
        DB::beginTransaction();
        
        try {
            // Calcular totais
            $cartSummary = $this->calculateCartSummary($cart);
            
            // Determinar valor final baseado no método de pagamento
            $finalTotal = $request->payment_method === 'pix' 
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
            
            // Criar pedido
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'subtotal' => $cartSummary['subtotal'],
                'shipping_cost' => $cartSummary['shipping_cost'],
                'discount_amount' => $request->payment_method === 'pix' ? $cartSummary['pix_discount'] : 0,
                'total_amount' => $finalTotal,
                
                // Dados do cliente
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                
                // Endereço de entrega
                'shipping_zipcode' => $request->zipcode,
                'shipping_address_line' => $request->address,
                'shipping_number' => $request->number,
                'shipping_complement' => $request->complement,
                'shipping_neighborhood' => $request->neighborhood,
                'shipping_city' => $request->city,
                'shipping_state' => $request->state,
                
                // Campos JSON para compatibilidade
                'shipping_address' => [
                    'zipcode' => $request->zipcode,
                    'address' => $request->address,
                    'number' => $request->number,
                    'complement' => $request->complement,
                    'neighborhood' => $request->neighborhood,
                    'city' => $request->city,
                    'state' => $request->state,
                ],
                'billing_address' => [
                    'zipcode' => $request->zipcode,
                    'address' => $request->address,
                    'number' => $request->number,
                    'complement' => $request->complement,
                    'neighborhood' => $request->neighborhood,
                    'city' => $request->city,
                    'state' => $request->state,
                ],
                
                // Campos de dropshipping
                'production_cost' => $productionCost,
                'supplier_shipping_cost' => $cartSummary['shipping_cost'], // Mesmo valor do frete do cliente
                'profit_margin' => $profitMargin,
                'production_days' => $maxProductionDays,
                'production_status' => 'pending',
                'supplier_status' => 'pending',
            ]);
            
            // Criar itens do pedido
            foreach ($cart as $item) {
                // Buscar o SKU do produto/variante para salvar no pedido
                $sku = null;
                if (!empty($item['variant_id'])) {
                    $variant = \App\Models\ProductVariant::find($item['variant_id']);
                    $sku = $variant->sku ?? null;
                }
                
                if (is_null($sku)) {
                    $product = \App\Models\Product::find($item['product_id']);
                    $sku = $product->sku ?? 'N/A'; // Fallback para evitar erro
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
                    'total_price' => $item['total'],
                ]);
            }
            
            // Se usuário está logado, atualizar seus dados
            if (Auth::check()) {
                Auth::user()->update([
                    'phone' => $request->customer_phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip_code' => $request->zipcode,
                ]);
            }
            
            DB::commit();
            
            // Limpar carrinho
            session()->forget('cart');
            
            return redirect()->route('checkout.success')
                            ->with('order', $order)
                            ->with('success', 'Pedido realizado com sucesso!');
                            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Adicionar log para depuração
            Log::error('Checkout Error: ' . $e->getMessage() . ' no arquivo ' . $e->getFile() . ' na linha ' . $e->getLine());
            
            return back()->withInput()->with('error', 'Erro ao processar pedido. Tente novamente.');
        }
    }
    
    /**
     * Página de sucesso
     */
    public function success()
    {
        $order = session('order');
        
        if (!$order) {
            return redirect()->route('home');
        }
        
        return view('checkout.success', compact('order'));
    }
    
    /**
     * Buscar CEP via API
     */
    public function searchZipcode(Request $request)
    {
        $request->validate([
            'zipcode' => 'required|string|min:8|max:9'
        ]);
        
        $zipcode = preg_replace('/[^0-9]/', '', $request->zipcode);
        
        if (strlen($zipcode) !== 8) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }
        
        try {
            $response = file_get_contents("https://viacep.com.br/ws/{$zipcode}/json/");
            $data = json_decode($response, true);
            
            if (isset($data['erro'])) {
                return response()->json(['error' => 'CEP não encontrado'], 404);
            }
            
            return response()->json([
                'address' => $data['logradouro'],
                'neighborhood' => $data['bairro'],
                'city' => $data['localidade'],
                'state' => $data['uf'],
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar CEP'], 500);
        }
    }
    
    /**
     * Calcular frete baseado no CEP
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'zipcode' => 'required|string|min:8|max:9'
        ]);
        
        $cart = session()->get('cart', []);
        $subtotal = array_sum(array_column($cart, 'total'));
        
        // Frete grátis acima de R$200
        if ($subtotal >= 200) {
            return response()->json([
                'shipping_cost' => 0,
                'shipping_days' => '5-7 dias úteis',
                'free_shipping' => true,
                'formatted_cost' => 'Grátis'
            ]);
        }
        
        // Calcular frete baseado na região (simulação)
        $zipcode = preg_replace('/[^0-9]/', '', $request->zipcode);
        $firstDigit = substr($zipcode, 0, 1);
        
        $shippingRates = [
            '0' => ['cost' => 12.90, 'days' => '3-5'],  // SP capital
            '1' => ['cost' => 15.90, 'days' => '5-7'],  // SP interior
            '2' => ['cost' => 18.90, 'days' => '5-8'],  // RJ
            '3' => ['cost' => 22.90, 'days' => '7-10'], // MG
            '4' => ['cost' => 25.90, 'days' => '8-12'], // Sul
            '5' => ['cost' => 28.90, 'days' => '10-15'], // Nordeste
        ];
        
        $rate = $shippingRates[$firstDigit] ?? ['cost' => 35.90, 'days' => '12-20'];
        
        return response()->json([
            'shipping_cost' => $rate['cost'],
            'shipping_days' => $rate['days'] . ' dias úteis',
            'free_shipping' => false,
            'formatted_cost' => 'R$ ' . number_format($rate['cost'], 2, ',', '.')
        ]);
    }
    
    /**
     * Gerar número do pedido
     */
    private function generateOrderNumber()
    {
        return 'VG' . date('Y') . date('m') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Calcular resumo do carrinho
     */
    private function calculateCartSummary($cart)
    {
        $subtotal = array_sum(array_column($cart, 'total'));
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        $freeShippingThreshold = 200.00;
        $standardShipping = 15.90;
        
        $shippingCost = $subtotal >= $freeShippingThreshold ? 0 : $standardShipping;
        $total = $subtotal + $shippingCost;
        
        $pixDiscount = $subtotal * 0.05;
        $pixTotal = $subtotal - $pixDiscount + $shippingCost;
        
        return [
            'subtotal' => $subtotal,
            'total_items' => $totalItems,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'pix_discount' => $pixDiscount,
            'pix_total' => $pixTotal,
            'formatted_subtotal' => 'R$ ' . number_format($subtotal, 2, ',', '.'),
            'formatted_shipping' => $shippingCost > 0 ? 'R$ ' . number_format($shippingCost, 2, ',', '.') : 'Grátis',
            'formatted_total' => 'R$ ' . number_format($total, 2, ',', '.'),
            'formatted_pix_total' => 'R$ ' . number_format($pixTotal, 2, ',', '.'),
        ];
    }
    
    /**
     * Obter itens do carrinho
     */
    private function getCartItems($cart)
    {
        $cartItems = [];
        
        foreach ($cart as $itemKey => $item) {
            $cartItems[] = [
                'key' => $itemKey,
                'name' => $item['name'],
                'size' => $item['size'],
                'color' => $item['color'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
                'formatted_unit_price' => 'R$ ' . number_format($item['unit_price'], 2, ',', '.'),
                'formatted_total' => 'R$ ' . number_format($item['total'], 2, ',', '.'),
            ];
        }
        
        return $cartItems;
    }
} 