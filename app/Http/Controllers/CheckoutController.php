<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\OrderCreationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Requests\ProcessCheckoutRequest;

class CheckoutController extends Controller
{
    protected $orderCreationService;
    protected $cartService;

    public function __construct(OrderCreationService $orderCreationService, CartService $cartService)
    {
        $this->orderCreationService = $orderCreationService;
        $this->cartService = $cartService;
    }

    /**
     * Exibir página de checkout
     */
    public function index()
    {
        $cart = $this->cartService->getContents();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Seu carrinho está vazio!');
        }
        
        $shippingCost = session()->get('shipping_cost'); // Pega o frete calculado no carrinho
        $cartSummary = $this->cartService->getCartSummary($cart, $shippingCost);
        $cartItems = $this->cartService->getCartItems($cart);
        
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

        // Se houver um CEP na sessão (calculado no carrinho), ele tem prioridade
        if (session()->has('shipping_zipcode')) {
            $userData['zipcode'] = session('shipping_zipcode');
        }
        
        return view('checkout.index', compact('cartItems', 'cartSummary', 'userData'));
    }
    
    /**
     * Processar pedido
     */
    public function process(ProcessCheckoutRequest $request)
    {
        $validatedData = $request->validated();
        
        $cart = $this->cartService->getContents();
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Carrinho vazio!');
        }
        
        DB::beginTransaction();
        
        try {
            // A lógica complexa agora está nos serviços
            $shippingCost = session()->get('shipping_cost'); // Pega o frete calculado no carrinho
            $cartSummary = $this->cartService->getCartSummary($cart, $shippingCost);
            
            $order = $this->orderCreationService->createOrder($validatedData, $cart, $cartSummary);
            
            // Se usuário está logado, atualizar seus dados
            if (Auth::check()) {
                Auth::user()->update([
                    'phone' => $validatedData['customer_phone'],
                    'address' => $validatedData['address'],
                    'city' => $validatedData['city'],
                    'state' => $validatedData['state'],
                    'zip_code' => $validatedData['zipcode'],
                ]);
            }
            
            DB::commit();
            
            // Limpar carrinho e redirecionar
            $this->cartService->clear();
            session()->forget('shipping_cost'); // Limpa também o frete da sessão
            session()->forget('shipping_zipcode');
            
            return redirect()->route('checkout.success')
                            ->with('order', $order)
                            ->with('success', 'Pedido realizado com sucesso!');
                            
        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Checkout Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
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
            
            return response()->json($data);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar CEP'], 500);
        }
    }
    
    /**
     * Calcular frete e salvar na sessão
     */
    public function calculateShipping(Request $request)
    {
        $request->validate(['zipcode' => 'required|string|min:8|max:9']);
        
        $zipcode = preg_replace('/[^0-9]/', '', $request->zipcode);
        
        if (strlen($zipcode) !== 8) {
            return response()->json(['error' => 'CEP inválido'], 400);
        }
        
        try {
            // Simulação de cálculo de frete
            // Em um cenário real, aqui seria a chamada para a API dos Correios
            $shippingCost = 25.00; // Custo fixo para fins de exemplo

            // Salva na sessão para ser usado no checkout
            session([
                'shipping_cost' => $shippingCost,
                'shipping_zipcode' => $zipcode
            ]);

            return response()->json([
                'success' => true,
                'shipping_cost' => $shippingCost,
                'formatted_shipping_cost' => 'R$ ' . number_format($shippingCost, 2, ',', '.'),
                'zipcode' => $zipcode,
                'message' => 'Frete calculado com sucesso!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao calcular frete'], 500);
        }
    }
    
    /**
     * Gerar número do pedido
     */
    private function generateOrderNumber()
    {
        // Este método não é mais usado aqui, pois a lógica foi movida para o serviço.
        // Pode ser removido em uma futura limpeza se não for usado em nenhum outro lugar.
        return 'VG' . str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT);
    }
} 