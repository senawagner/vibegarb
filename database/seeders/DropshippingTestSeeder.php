<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class DropshippingTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin se não existir
        $user = User::firstOrCreate(
            ['email' => 'admin@vibegarb.com'],
            [
                'name' => 'Admin Vibe Garb',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        // Criar produtos se não existirem
        $products = [
            [
                'name' => 'Camiseta Básica Premium',
                'slug' => 'camiseta-basica-premium',
                'sku' => 'CBP001',
                'description' => 'Camiseta de algodão 100% premium, ideal para o dia a dia',
                'price' => 89.90,
                'short_description' => 'Camiseta básica de alta qualidade',
                'quality_line' => 'prime',
                'is_active' => true,
                'is_featured' => true,
                'category_id' => 1,
            ],
            [
                'name' => 'Moletom Casual Comfort',
                'slug' => 'moletom-casual-comfort',
                'sku' => 'MCC001',
                'description' => 'Moletom confortável e elegante para momentos de lazer',
                'price' => 129.90,
                'short_description' => 'Moletom casual super confortável',
                'quality_line' => 'quality',
                'is_active' => true,
                'is_featured' => true,
                'category_id' => 1,
            ],
            [
                'name' => 'Calça Jeans Slim Fit',
                'slug' => 'calca-jeans-slim-fit',
                'sku' => 'CJS001',
                'description' => 'Calça jeans moderna com caimento perfeito',
                'price' => 159.90,
                'short_description' => 'Calça jeans slim fit moderna',
                'quality_line' => 'prime',
                'is_active' => true,
                'is_featured' => false,
                'category_id' => 1,
            ]
        ];

        foreach ($products as $productData) {
            Product::firstOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }

        // Criar pedidos de teste com diferentes status
        $orders = [
            [
                'order_number' => 'DS001',
                'customer_name' => 'Maria Silva',
                'customer_email' => 'maria@teste.com',
                'customer_phone' => '11988888888',
                'payment_method' => 'pix',
                'payment_status' => 'completed',
                'status' => 'pending',
                'subtotal' => 89.90,
                'shipping_cost' => 15.00,
                'discount_amount' => 0,
                'total_amount' => 104.90,
                'shipping_zipcode' => '01310-100',
                'shipping_address' => 'Av Paulista, 1000',
                'shipping_number' => '100',
                'shipping_neighborhood' => 'Bela Vista',
                'shipping_city' => 'São Paulo',
                'shipping_state' => 'SP',
                'production_status' => 'pending',
                'supplier_status' => 'pending',
                'production_cost' => 59.90,
                'profit_margin' => 45.00,
                'production_days' => 7,
                'supplier_notes' => 'Pedido urgente - cliente VIP',
                'internal_notes' => 'Cliente solicitou entrega rápida',
            ],
            [
                'order_number' => 'DS002',
                'customer_name' => 'João Santos',
                'customer_email' => 'joao@teste.com',
                'customer_phone' => '11977777777',
                'payment_method' => 'pix',
                'payment_status' => 'completed',
                'status' => 'pending',
                'subtotal' => 129.90,
                'shipping_cost' => 0,
                'discount_amount' => 10.00,
                'total_amount' => 119.90,
                'shipping_zipcode' => '20040-007',
                'shipping_address' => 'Rua do Ouvidor, 50',
                'shipping_number' => '50',
                'shipping_neighborhood' => 'Centro',
                'shipping_city' => 'Rio de Janeiro',
                'shipping_state' => 'RJ',
                'production_status' => 'confirmed',
                'supplier_status' => 'confirmed',
                'production_cost' => 89.90,
                'profit_margin' => 30.00,
                'production_days' => 5,
                'supplier_notes' => 'Produto com acabamento especial',
                'internal_notes' => 'Cliente fiel - segunda compra',
            ],
            [
                'order_number' => 'DS003',
                'customer_name' => 'Ana Costa',
                'customer_email' => 'ana@teste.com',
                'customer_phone' => '11966666666',
                'payment_method' => 'pix',
                'payment_status' => 'completed',
                'status' => 'pending',
                'subtotal' => 159.90,
                'shipping_cost' => 20.00,
                'discount_amount' => 0,
                'total_amount' => 179.90,
                'shipping_zipcode' => '40170-010',
                'shipping_address' => 'Rua Chile, 25',
                'shipping_number' => '25',
                'shipping_neighborhood' => 'Pelourinho',
                'shipping_city' => 'Salvador',
                'shipping_state' => 'BA',
                'production_status' => 'in_production',
                'supplier_status' => 'confirmed',
                'production_cost' => 119.90,
                'profit_margin' => 60.00,
                'production_days' => 10,
                'supplier_notes' => 'Produto com personalização',
                'internal_notes' => 'Cliente novo - primeira compra',
            ],
            [
                'order_number' => 'DS004',
                'customer_name' => 'Pedro Lima',
                'customer_email' => 'pedro@teste.com',
                'customer_phone' => '11955555555',
                'payment_method' => 'pix',
                'payment_status' => 'completed',
                'status' => 'pending',
                'subtotal' => 89.90,
                'shipping_cost' => 15.00,
                'discount_amount' => 5.00,
                'total_amount' => 99.90,
                'shipping_zipcode' => '90020-004',
                'shipping_address' => 'Rua dos Andradas, 100',
                'shipping_number' => '100',
                'shipping_neighborhood' => 'Centro Histórico',
                'shipping_city' => 'Porto Alegre',
                'shipping_state' => 'RS',
                'production_status' => 'ready_to_ship',
                'supplier_status' => 'confirmed',
                'production_cost' => 59.90,
                'profit_margin' => 40.00,
                'production_days' => 7,
                'supplier_notes' => 'Produto pronto para envio',
                'internal_notes' => 'Aguardando confirmação de envio',
            ],
            [
                'order_number' => 'DS005',
                'customer_name' => 'Lucia Ferreira',
                'customer_email' => 'lucia@teste.com',
                'customer_phone' => '11944444444',
                'payment_method' => 'pix',
                'payment_status' => 'completed',
                'status' => 'pending',
                'subtotal' => 129.90,
                'shipping_cost' => 0,
                'discount_amount' => 0,
                'total_amount' => 129.90,
                'shipping_zipcode' => '80020-110',
                'shipping_address' => 'Rua XV de Novembro, 200',
                'shipping_number' => '200',
                'shipping_neighborhood' => 'Centro',
                'shipping_city' => 'Curitiba',
                'shipping_state' => 'PR',
                'production_status' => 'shipped',
                'supplier_status' => 'shipped',
                'production_cost' => 89.90,
                'profit_margin' => 40.00,
                'production_days' => 5,
                'supplier_notes' => 'Produto enviado com sucesso',
                'internal_notes' => 'Tracking: BR123456789BR',
                'supplier_tracking_code' => 'BR123456789BR',
                'supplier_tracking_url' => 'https://rastreamento.correios.com.br/app/index.php?objeto=BR123456789BR',
            ]
        ];

        foreach ($orders as $orderData) {
            $order = Order::firstOrCreate(
                ['order_number' => $orderData['order_number']],
                array_merge($orderData, ['user_id' => $user->id])
            );

            // Criar item do pedido
            $product = Product::inRandomOrder()->first();
            if ($product) {
                OrderItem::firstOrCreate(
                    [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'product_name' => $product->name,
                        'product_sku' => $product->sku ?? 'SKU' . $product->id,
                        'quantity' => rand(1, 3),
                        'unit_price' => $product->price,
                        'total_price' => $product->price,
                        'production_cost' => $product->price * 0.6, // 60% do preço como custo
                    ]
                );
            }
        }

        $this->command->info('Dados de teste para dropshipping criados com sucesso!');
        $this->command->info('Pedidos criados: ' . Order::count());
    }
}
