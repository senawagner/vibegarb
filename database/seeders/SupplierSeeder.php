<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Fashion Print Brasil',
                'email' => 'vendas@fashionprint.com.br',
                'phone' => '(11) 3456-7890',
                'address' => 'Rua da Consolação, 1000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'zip_code' => '01302-000',
                'contact_person' => 'Carlos Oliveira',
                'commission_rate' => 30.00,
                'is_active' => true
            ],
            [
                'name' => 'Urban Style Confecções',
                'email' => 'contato@urbanstyle.com.br',
                'phone' => '(21) 2987-6543',
                'address' => 'Av. Rio Branco, 500',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'zip_code' => '20040-020',
                'contact_person' => 'Ana Silva',
                'commission_rate' => 25.00,
                'is_active' => true
            ],
            [
                'name' => 'Street Wear Factory',
                'email' => 'pedidos@streetwear.com.br',
                'phone' => '(31) 3214-5678',
                'address' => 'Rua Bahia, 789',
                'city' => 'Belo Horizonte',
                'state' => 'MG',
                'zip_code' => '30160-011',
                'contact_person' => 'Pedro Santos',
                'commission_rate' => 35.00,
                'is_active' => true
            ],
            [
                'name' => 'Eco Threads Sustentável',
                'email' => 'eco@ecothreads.com.br',
                'phone' => '(47) 3344-5566',
                'address' => 'Rua das Palmeiras, 234',
                'city' => 'Blumenau',
                'state' => 'SC',
                'zip_code' => '89010-100',
                'contact_person' => 'Marina Costa',
                'commission_rate' => 40.00,
                'is_active' => true
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
