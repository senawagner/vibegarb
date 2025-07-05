<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class SimpleDropshippingSeeder extends Seeder
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
                'shipping_address_line' => 'Av Paulista, 1000',
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
                'billing_address' => json_encode([
                    'name' => 'Maria Silva',
                    'email' => 'maria@teste.com',
                    'phone' => '11988888888',
                    'address' => 'Av Paulista, 1000',
                    'number' => '100',
                    'neighborhood' => 'Bela Vista',
                    'city' => 'São Paulo',
                    'state' => 'SP',
                    'zipcode' => '01310-100'
                ]),
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
                'shipping_address_line' => 'Rua do Ouvidor, 50',
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
                'billing_address' => json_encode([
                    'name' => 'João Santos',
                    'email' => 'joao@teste.com',
                    'phone' => '11977777777',
                    'address' => 'Rua do Ouvidor, 50',
                    'number' => '50',
                    'neighborhood' => 'Centro',
                    'city' => 'Rio de Janeiro',
                    'state' => 'RJ',
                    'zipcode' => '20040-007'
                ]),
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
                'shipping_address_line' => 'Rua Chile, 25',
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
                'billing_address' => json_encode([
                    'name' => 'Ana Costa',
                    'email' => 'ana@teste.com',
                    'phone' => '11966666666',
                    'address' => 'Rua Chile, 25',
                    'number' => '25',
                    'neighborhood' => 'Pelourinho',
                    'city' => 'Salvador',
                    'state' => 'BA',
                    'zipcode' => '40170-010'
                ]),
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
                'shipping_address_line' => 'Rua dos Andradas, 100',
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
                'billing_address' => json_encode([
                    'name' => 'Pedro Lima',
                    'email' => 'pedro@teste.com',
                    'phone' => '11955555555',
                    'address' => 'Rua dos Andradas, 100',
                    'number' => '100',
                    'neighborhood' => 'Centro Histórico',
                    'city' => 'Porto Alegre',
                    'state' => 'RS',
                    'zipcode' => '90020-004'
                ]),
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
                'shipping_address_line' => 'Rua XV de Novembro, 200',
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
                'billing_address' => json_encode([
                    'name' => 'Lucia Ferreira',
                    'email' => 'lucia@teste.com',
                    'phone' => '11944444444',
                    'address' => 'Rua XV de Novembro, 200',
                    'number' => '200',
                    'neighborhood' => 'Centro',
                    'city' => 'Curitiba',
                    'state' => 'PR',
                    'zipcode' => '80020-110'
                ]),
            ]
        ];

        foreach ($orders as $orderData) {
            Order::firstOrCreate(
                ['order_number' => $orderData['order_number']],
                array_merge($orderData, ['user_id' => $user->id])
            );
        }

        $this->command->info('Pedidos de teste para dropshipping criados com sucesso!');
        $this->command->info('Pedidos criados: ' . Order::count());
    }
}
