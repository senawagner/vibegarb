<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Produtos básicos para testar o sistema admin
        $products = [
            [
                'name' => 'Camiseta Básica Branca',
                'slug' => 'camiseta-basica-branca',
                'description' => 'Camiseta básica 100% algodão branca, macia e confortável.',
                'sku' => 'VG-001',
                'price' => 39.90,
                'stock_quantity' => 100,
                'category_id' => 1,
                'quality_line' => 'classic',
                'is_active' => true,
            ],
            [
                'name' => 'Camiseta Básica Preta',
                'slug' => 'camiseta-basica-preta',
                'description' => 'Camiseta básica 100% algodão preta, versátil e elegante.',
                'sku' => 'VG-002',
                'price' => 39.90,
                'stock_quantity' => 100,
                'category_id' => 1,
                'quality_line' => 'classic',
                'is_active' => true,
            ],
            [
                'name' => 'Camiseta Premium Urban',
                'slug' => 'camiseta-premium-urban',
                'description' => 'Camiseta premium com design urbano moderno.',
                'sku' => 'VG-003',
                'price' => 59.90,
                'stock_quantity' => 50,
                'category_id' => 2,
                'quality_line' => 'prime',
                'is_active' => true,
            ],
            [
                'name' => 'Regata Esportiva',
                'slug' => 'regata-esportiva',
                'description' => 'Regata ideal para atividades esportivas e lazer.',
                'sku' => 'VG-004',
                'price' => 34.90,
                'stock_quantity' => 75,
                'category_id' => 5,
                'quality_line' => 'dry_sport',
                'is_active' => true,
            ],
            [
                'name' => 'Camiseta Oversized Street',
                'slug' => 'camiseta-oversized-street',
                'description' => 'Camiseta oversized estilo street wear.',
                'sku' => 'VG-005',
                'price' => 69.90,
                'stock_quantity' => 30,
                'category_id' => 3,
                'quality_line' => 'quality',
                'is_active' => true,
            ]
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
} 