<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\PendingRequest;
use Exception;
use Illuminate\Support\Facades\Log;

class DimonaService
{
    /**
     * O cliente HTTP pré-configurado para a API da Dimona.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $client;

    /**
     * Construtor do serviço da Dimona.
     * Configura o cliente HTTP com a URL base e a chave da API.
     */
    public function __construct()
    {
        $config = config('services.dimona');

        if (empty($config['key']) || empty($config['domain'])) {
            throw new Exception('As credenciais da API da Dimona não estão configuradas corretamente.');
        }

        $this->client = Http::baseUrl($config['domain'])
            ->withHeaders([
                'api-key' => $config['key'],
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->timeout(30);
    }

    /**
     * Envia um pedido para a API da Dimona.
     *
     * @param Order $order
     * @return array A resposta da API.
     * @throws Exception Se ocorrer um erro na comunicação.
     */
    public function sendOrder(Order $order): array
    {
        // Garante que os relacionamentos necessários estejam carregados
        $order->loadMissing('items.product.supplier');

        // 1. Mapear os dados do nosso pedido para o formato da API
        $payload = [
            'shipping_speed' => 'pac', // Futuramente, isso pode vir do pedido (ex: $order->shipping_method)
            'order_id' => $order->order_number,
            'customer_name' => $order->customer_name,
            'customer_document' => preg_replace('/[^0-9]/', '', $order->customer_document ?? ''),
            'customer_email' => $order->customer_email,
            'items' => [],
            'address' => [
                'name' => $order->customer_name,
                'street' => $order->shipping_street,
                'number' => $order->shipping_number,
                'complement' => $order->shipping_complement,
                'city' => $order->shipping_city,
                'state' => $order->shipping_state,
                'zipcode' => preg_replace('/[^0-9]/', '', $order->shipping_zipcode ?? ''),
                'neighborhood' => $order->shipping_neighborhood,
                'phone' => preg_replace('/[^0-9]/', '', $order->customer_phone ?? ''),
                'country' => 'BR',
            ],
            // 'nfe' => [], // Opcional, pode ser adicionado no futuro
            // 'webhook_url' => route('webhooks.dimona'), // Opcional, mas recomendado para o futuro
        ];

        // 2. Iterar sobre os orderItems para construir o array de items
        foreach ($order->items as $item) {
            // Idealmente, o 'dimona_sku_id' viria de um campo específico no modelo Product ou ProductVariant
            // Por enquanto, usaremos o SKU do nosso sistema como placeholder.
            $dimonaSku = $item->product->supplier_sku ?? $item->product_sku;

            $payload['items'][] = [
                'name' => $item->product_name,
                'sku' => $item->product_sku, // SKU interno para nossa referência
                'qty' => $item->quantity,
                'dimona_sku_id' => $dimonaSku, // O SKU que a Dimona reconhece
                'designs' => [], // Placeholder. No futuro, pode vir de um campo do produto com as URLs das artes.
                'mocks' => [],   // Placeholder para os mockups das imagens.
            ];
        }

        // 3. Fazer a chamada POST
        Log::info('Enviando pedido para a API da Dimona.', ['order_number' => $order->order_number, 'payload' => $payload]);
        $response = $this->client->post('/api/v2/order', $payload);

        // 4. Lidar com a resposta
        if ($response->failed()) {
            // Log detalhado do erro para depuração
            Log::error('Erro ao enviar pedido para a API da Dimona.', [
                'order_number' => $order->order_number,
                'status' => $response->status(),
                'response_body' => $response->body(),
                'payload_sent' => $payload,
            ]);

            // Lança uma exceção para que o controller possa tratar o erro de forma mais específica
            $response->throw();
        }

        Log::info('Pedido enviado com sucesso para a Dimona.', [
            'order_number' => $order->order_number, 
            'dimona_response' => $response->json()
        ]);

        // Retorna o corpo da resposta em caso de sucesso
        return $response->json();
    }
} 