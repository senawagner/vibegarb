<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Requests\AddToCartRequest; // Futuramente, podemos criar este FormRequest

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Exibe a página do carrinho de compras.
     */
    public function index()
    {
        $cartItems = $this->cartService->getCollection();
        $subtotal = $this->cartService->getSubtotal();
        $itemCount = $this->cartService->getTotalQuantity();
        
        // Lógica de frete e total pode ser adicionada aqui ou em outro serviço
        $shippingCost = $subtotal > 200 ? 0 : 15; // Exemplo simples
        $total = $subtotal + $shippingCost;

        // Preparar dados do resumo do carrinho
        $cartSummary = [
            'total_items' => $itemCount,
            'formatted_subtotal' => 'R$ ' . number_format($subtotal, 2, ',', '.'),
            'formatted_shipping' => $shippingCost > 0 ? 'R$ ' . number_format($shippingCost, 2, ',', '.') : 'Grátis',
            'has_free_shipping' => $subtotal >= 200,
            'formatted_free_shipping_remaining' => 'R$ ' . number_format(max(0, 200 - $subtotal), 2, ',', '.'),
            'formatted_total' => 'R$ ' . number_format($total, 2, ',', '.'),
            'formatted_pix_total' => 'R$ ' . number_format($total * 0.95, 2, ',', '.'), // 5% de desconto no PIX
        ];

        return view('cart.index', compact('cartItems', 'subtotal', 'shippingCost', 'total', 'itemCount', 'cartSummary'));
    }

    /**
     * Adiciona um produto ao carrinho.
     */
    public function add(Request $request)
    {
        // Validação básica por enquanto
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'color' => 'required|string',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $itemAdded = $this->cartService->add($validatedData);

        return response()->json([
            'success' => true,
            'item' => $itemAdded,
            'cartSummary' => $this->getCartSummary(),
        ]);
    }

    /**
     * Atualiza a quantidade de um item no carrinho.
     */
    public function update(Request $request, $itemKey)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:0|max:10' // min:0 para permitir remoção
        ]);

        $this->cartService->update($itemKey, $validatedData['quantity']);

        return response()->json([
            'success' => true,
            'cartSummary' => $this->getCartSummary(),
        ]);
    }

    /**
     * Remove um item do carrinho.
     */
    public function remove($itemKey)
    {
        $this->cartService->remove($itemKey);
        
        return response()->json([
            'success' => true,
            'cartSummary' => $this->getCartSummary(),
        ]);
    }

    /**
     * Limpa todo o carrinho.
     */
    public function clear()
    {
        $this->cartService->clear();

        return response()->json([
            'success' => true,
            'message' => 'Todos os itens foram removidos do carrinho.',
            'cartSummary' => $this->getCartSummary(),
        ]);
    }

    /**
     * Retorna a contagem de itens no carrinho.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        $count = $this->cartService->getTotalQuantity();
        return response()->json(['count' => $count]);
    }

    /**
     * Retorna um resumo dos dados do carrinho para respostas JSON.
     */
    private function getCartSummary(): array
    {
        $subtotal = $this->cartService->getSubtotal();
        $shippingCost = $subtotal > 200 ? 0 : 15; // Exemplo simples
        
        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $subtotal + $shippingCost,
            'item_count' => $this->cartService->getTotalQuantity(),
        ];
    }
}
