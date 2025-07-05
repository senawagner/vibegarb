<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Exibir o carrinho
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        $cartSummary = $this->calculateCartSummary($cartItems);
        $itemCount = count($cartItems);
        
        return view('cart.index', compact('cartItems', 'cartSummary', 'itemCount'));
    }

    /**
     * Adicionar produto ao carrinho - TEMPORÁRIO SEM VALIDAÇÃO
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'color' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Verificar se o produto está ativo
        if (!$product->is_active) {
            return back()->with('error', 'Produto indisponível no momento.');
        }

        // Calcular preço (usando preço base)
        $unitPrice = $product->price;
        
        // Criar identificador único para o item
        $itemKey = $this->generateItemKey($request->product_id, $request->size, $request->color);
        
        // Recuperar carrinho atual
        $cart = session()->get('cart', []);
        
        // Se item já existe, somar a quantidade
        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $request->quantity;
            $cart[$itemKey]['total'] = $cart[$itemKey]['quantity'] * $cart[$itemKey]['unit_price'];
        } else {
            // Adicionar novo item
            $cart[$itemKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity,
                'unit_price' => $unitPrice,
                'total' => $unitPrice * $request->quantity,
                'image' => $product->primary_image_url ?? '',
                'quality_line' => $product->quality_line,
                'quality_description' => $product->quality_description
            ];
        }
        
        // Salvar carrinho na sessão
        session()->put('cart', $cart);
        
        return back()->with('success', 'Produto adicionado ao carrinho com sucesso! 🛒');
    }

    /**
     * Atualizar quantidade de um item
     */
    public function update(Request $request, $itemKey)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $cart = session()->get('cart', []);
        
        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] = $request->quantity;
            $cart[$itemKey]['total'] = $cart[$itemKey]['quantity'] * $cart[$itemKey]['unit_price'];
            
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'item' => $cart[$itemKey],
                'cartSummary' => $this->calculateCartSummary(array_values($cart))
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Item não encontrado']);
    }

    /**
     * Remover item do carrinho
     */
    public function remove($itemKey)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$itemKey])) {
            unset($cart[$itemKey]);
            session()->put('cart', $cart);
            
            return response()->json([
                'success' => true,
                'cartSummary' => $this->calculateCartSummary(array_values($cart)),
                'itemCount' => count($cart)
            ]);
        }
        
        return response()->json(['success' => false, 'message' => 'Item não encontrado']);
    }

    /**
     * Limpar carrinho completamente
     */
    public function clear()
    {
        session()->forget('cart');
        
        return response()->json([
            'success' => true,
            'message' => 'Carrinho esvaziado',
            'cartSummary' => $this->calculateCartSummary([]),
            'itemCount' => 0
        ]);
    }

    /**
     * API para obter dados do carrinho (para header)
     */
    public function count()
    {
        $cart = session()->get('cart', []);
        $totalItems = array_sum(array_column($cart, 'quantity'));
        
        return response()->json([
            'count' => $totalItems,
            'items' => count($cart)
        ]);
    }

    /**
     * Recuperar itens do carrinho com dados completos
     */
    private function getCartItems()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        
        foreach ($cart as $itemKey => $item) {
            // Verificar se o produto ainda existe e está ativo
            $product = Product::find($item['product_id']);
            
            if ($product && $product->is_active) {
                $cartItems[] = [
                    'key' => $itemKey,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'slug' => $item['slug'],
                    'size' => $item['size'],
                    'color' => $item['color'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                    'image' => $item['image'] ?: $product->primary_image_url,
                    'quality_line' => $item['quality_line'],
                    'quality_description' => $item['quality_description'],
                    'formatted_unit_price' => 'R$ ' . number_format($item['unit_price'], 2, ',', '.'),
                    'formatted_total' => 'R$ ' . number_format($item['total'], 2, ',', '.'),
                    'product_url' => route('products.show', $item['slug'])
                ];
            } else {
                // Remover itens de produtos inativos/inexistentes
                $this->removeInvalidItem($itemKey);
            }
        }
        
        return $cartItems;
    }

    /**
     * Calcular resumo do carrinho
     */
    private function calculateCartSummary($cartItems)
    {
        $subtotal = array_sum(array_column($cartItems, 'total'));
        $totalItems = array_sum(array_column($cartItems, 'quantity'));
        
        // Regras de frete (temporário - será calculado via API)
        $freeShippingThreshold = 200.00;
        $standardShipping = 15.90;
        
        $shippingCost = $subtotal >= $freeShippingThreshold ? 0 : $standardShipping;
        $total = $subtotal + $shippingCost;
        
        // Desconto PIX (5%)
        $pixDiscount = $subtotal * 0.05;
        $pixTotal = $subtotal - $pixDiscount + $shippingCost;
        
        return [
            'subtotal' => $subtotal,
            'total_items' => $totalItems,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'pix_discount' => $pixDiscount,
            'pix_total' => $pixTotal,
            'free_shipping_threshold' => $freeShippingThreshold,
            'free_shipping_remaining' => max(0, $freeShippingThreshold - $subtotal),
            'has_free_shipping' => $subtotal >= $freeShippingThreshold,
            'formatted_subtotal' => 'R$ ' . number_format($subtotal, 2, ',', '.'),
            'formatted_shipping' => $shippingCost > 0 ? 'R$ ' . number_format($shippingCost, 2, ',', '.') : 'Grátis',
            'formatted_total' => 'R$ ' . number_format($total, 2, ',', '.'),
            'formatted_pix_total' => 'R$ ' . number_format($pixTotal, 2, ',', '.'),
            'formatted_free_shipping_remaining' => 'R$ ' . number_format(max(0, $freeShippingThreshold - $subtotal), 2, ',', '.')
        ];
    }

    /**
     * Gerar chave única para item do carrinho
     */
    private function generateItemKey($productId, $size, $color)
    {
        return $productId . '_' . $size . '_' . $color;
    }

    /**
     * Remover item inválido do carrinho
     */
    private function removeInvalidItem($itemKey)
    {
        $cart = session()->get('cart', []);
        unset($cart[$itemKey]);
        session()->put('cart', $cart);
    }
}
