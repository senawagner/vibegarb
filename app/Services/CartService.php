<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Collection;

class CartService
{
    protected $session;
    protected $cartSessionKey = 'shopping_cart';

    const FRETE_GRATIS_ACIMA_DE = 200.00;
    const CUSTO_FRETE_PADRAO = 25.00;
    const DESCONTO_PIX_PERCENTUAL = 0.05;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Adiciona um item ao carrinho.
     */
    public function add(array $data): array
    {
        $cart = $this->getContents();
        $product = Product::findOrFail($data['product_id']);
        $variantKey = $this->createVariantKey($data);

        if (isset($cart[$variantKey])) {
            // Se o item já existe, apenas atualiza a quantidade
            $cart[$variantKey]['quantity'] += $data['quantity'];
        } else {
            // Se for um novo item, adiciona ao carrinho
            $cart[$variantKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'size' => $data['size'],
                'color' => $data['color'],
                'quantity' => $data['quantity'],
                'unit_price' => $product->price,
                'image' => $product->primary_image_url,
                'quality_line' => $product->quality_line,
                'quality_description' => $product->quality_description
            ];
        }

        $this->session->put($this->cartSessionKey, $cart);

        return $cart[$variantKey];
    }

    /**
     * Atualiza a quantidade de um item no carrinho.
     */
    public function update(string $variantKey, int $quantity): array
    {
        $cart = $this->getContents();

        if (isset($cart[$variantKey])) {
            if ($quantity > 0) {
                $cart[$variantKey]['quantity'] = $quantity;
            } else {
                unset($cart[$variantKey]);
            }
        }
        
        $this->session->put($this->cartSessionKey, $cart);
        
        return $cart;
    }

    /**
     * Remove um item do carrinho.
     */
    public function remove(string $variantKey): array
    {
        $cart = $this->getContents();
        unset($cart[$variantKey]);
        $this->session->put($this->cartSessionKey, $cart);
        return $cart;
    }

    /**
     * Limpa todo o carrinho.
     */
    public function clear(): void
    {
        $this->session->forget($this->cartSessionKey);
    }
    
    /**
     * Retorna todo o conteúdo do carrinho.
     */
    public function getContents(): array
    {
        return $this->session->get($this->cartSessionKey, []);
    }

    /**
     * Retorna o conteúdo do carrinho como uma Collection.
     */
    public function getCollection(): Collection
    {
        $cart = $this->getContents();
        
        // Mapeia os itens para incluir a chave (key) em cada item
        $cartWithKeys = [];
        foreach ($cart as $key => $item) {
            $item['key'] = $key;
            $item['formatted_unit_price'] = 'R$ ' . number_format($item['unit_price'], 2, ',', '.');
            $item['formatted_total'] = 'R$ ' . number_format($item['quantity'] * $item['unit_price'], 2, ',', '.');
            $item['product_url'] = route('products.show', $item['slug']);
            $cartWithKeys[] = $item;
        }
        
        return new Collection($cartWithKeys);
    }

    /**
     * Retorna a quantidade total de itens no carrinho.
     */
    public function getTotalQuantity(): int
    {
        return $this->getCollection()->sum('quantity');
    }

    /**
     * Calcula o subtotal dos itens no carrinho.
     */
    public function getSubtotal(): float
    {
        return $this->getCollection()->sum(function ($item) {
            return $item['quantity'] * $item['unit_price'];
        });
    }

    /**
     * Cria uma chave única para a variação do produto (produto + cor + tamanho).
     */
    protected function createVariantKey(array $data): string
    {
        return md5($data['product_id'] . '_' . $data['color'] . '_' . $data['size']);
    }

    /**
     * Calcula o resumo do carrinho, incluindo subtotais, frete, descontos e total.
     *
     * @param array $cart O carrinho da sessão.
     * @param float|null $shippingCostOverride Custo de frete calculado externamente (ex: API Correios).
     * @return array
     */
    public function getCartSummary(array $cart, ?float $shippingCostOverride = null): array
    {
        $subtotal = 0;
        $itemCount = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
            $itemCount += $item['quantity'];
        }

        // Usa o frete calculado externamente se disponível, senão aplica a regra de negócio
        $shippingCost = $shippingCostOverride ?? ($subtotal >= self::FRETE_GRATIS_ACIMA_DE ? 0 : self::CUSTO_FRETE_PADRAO);
        
        $total = $subtotal + $shippingCost;
        $pixDiscount = $subtotal * self::DESCONTO_PIX_PERCENTUAL;
        $pixTotal = $total - $pixDiscount;

        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'total' => $total,
            'pix_discount' => $pixDiscount,
            'pix_total' => $pixTotal,
            'item_count' => $itemCount,
            'formatted_subtotal' => 'R$ ' . number_format($subtotal, 2, ',', '.'),
            'formatted_shipping' => $shippingCost > 0 ? 'R$ ' . number_format($shippingCost, 2, ',', '.') : 'Grátis',
            'formatted_total' => 'R$ ' . number_format($total, 2, ',', '.'),
            'formatted_pix_total' => 'R$ ' . number_format($pixTotal, 2, ',', '.'),
            'has_free_shipping' => $subtotal >= self::FRETE_GRATIS_ACIMA_DE,
            'formatted_free_shipping_remaining' => $subtotal < self::FRETE_GRATIS_ACIMA_DE ? 
                'R$ ' . number_format(self::FRETE_GRATIS_ACIMA_DE - $subtotal, 2, ',', '.') : null,
            'total_items' => $itemCount
        ];
    }

    /**
     * Obtém os itens do carrinho com seus modelos de produto associados para exibição.
     *
     * @param array $cart
     * @return Collection
     */
    public function getCartItems(array $cart): Collection
    {
        $productIds = array_column($cart, 'product_id');
        $products = Product::with('productImages')->whereIn('id', $productIds)->get()->keyBy('id');

        return collect($cart)->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);
            $item['product'] = $product;
            // Garante uma imagem padrão caso o produto não tenha
            $item['image'] = $product && $product->productImages->isNotEmpty() ? $product->productImages->first()->image_path : '/images/default-product.png';
            return (object) $item;
        });
    }
} 