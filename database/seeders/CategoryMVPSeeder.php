<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategoryMVPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Atualizar categorias existentes com ícones
        Category::where('slug', 'basicas')->update(['image' => '👕']);
        Category::where('slug', 'estampadas')->update(['image' => '🎨']);
        Category::where('slug', 'oversized')->update(['image' => '👔']);
        
        // Criar categorias MVP se não existirem
        $mvpCategories = [
            [
                'name' => 'Moletons',
                'slug' => 'moletons',
                'description' => 'Blusões, cangurus e moletons com zíper',
                'image' => '🧥',
                'sort_order' => 7,
                'is_active' => true
            ],
            [
                'name' => 'Plus Size',
                'slug' => 'plus-size',
                'description' => 'Tamanhos especiais para todos os tipos',
                'image' => '👔',
                'sort_order' => 8,
                'is_active' => true
            ],
            [
                'name' => 'Infantil',
                'slug' => 'infantil',
                'description' => 'Camisetas e bodies para crianças',
                'image' => '👶',
                'sort_order' => 9,
                'is_active' => true
            ],
            [
                'name' => 'Acessórios',
                'slug' => 'acessorios',
                'description' => 'Canecas, bolsas, bonés e mais',
                'image' => '🎒',
                'sort_order' => 10,
                'is_active' => true
            ]
        ];

        foreach ($mvpCategories as $categoryData) {
            Category::firstOrCreate(
                ['slug' => $categoryData['slug']], 
                $categoryData
            );
        }

        // Criar produtos de exemplo para o MVP
        $this->createMVPProducts();
    }

    private function createMVPProducts()
    {
        $camisetasCategory = Category::where('slug', 'basicas')->first();
        $moletonsCategory = Category::where('slug', 'moletons')->first();
        
        if ($camisetasCategory) {
            // Camiseta Tech Modal (todas as qualidades)
            $qualities = ['classic', 'quality', 'prime', 'pima', 'estonada', 'dry_sport'];
            
            foreach ($qualities as $quality) {
                Product::firstOrCreate(
                    ['slug' => 'camiseta-tech-modal-' . $quality],
                    [
                        'name' => 'Camiseta Tech Modal ' . ucfirst($quality),
                        'description' => 'Camiseta com tecnologia modal e recursos avançados da linha ' . $quality,
                        'short_description' => 'Tech Modal - Linha ' . ucfirst($quality),
                        'sku' => 'TM-' . strtoupper($quality) . '-001',
                        'price' => $this->getPriceByQuality($quality),
                        'category_id' => $camisetasCategory->id,
                        'quality_line' => $quality,
                        'target_audience' => 'unissex',
                        'available_colors' => 'Preto,Branco,Cinza,Azul,Verde',
                        'available_sizes' => 'PP,P,M,G,GG,XG',
                        'is_active' => true,
                        'is_featured' => in_array($quality, ['prime', 'pima']),
                        'weight' => 0.2,
                        'images' => [
                            '/images/products/tech-modal-' . $quality . '-front.jpg',
                            '/images/products/tech-modal-' . $quality . '-back.jpg'
                        ]
                    ]
                );
            }
        }

        if ($moletonsCategory) {
            // Moletom Canguru
            Product::firstOrCreate(
                ['slug' => 'moletom-canguru-premium'],
                [
                    'name' => 'Moletom Canguru Premium',
                    'description' => 'Moletom canguru com capuz e bolso central, qualidade premium',
                    'short_description' => 'Moletom Canguru - Premium',
                    'sku' => 'MC-PREM-001',
                    'price' => 149.90,
                    'category_id' => $moletonsCategory->id,
                    'quality_line' => 'prime',
                    'target_audience' => 'unissex',
                    'available_colors' => 'Preto,Cinza,Azul Marinho,Bordô',
                    'available_sizes' => 'P,M,G,GG,XG',
                    'is_active' => true,
                    'is_featured' => true,
                    'weight' => 0.6,
                    'images' => [
                        '/images/products/moletom-canguru-front.jpg',
                        '/images/products/moletom-canguru-back.jpg'
                    ]
                ]
            );
        }
    }

    private function getPriceByQuality($quality)
    {
        $basePrices = [
            'classic' => 39.90,
            'quality' => 59.90,
            'prime' => 89.90,
            'pima' => 119.90,
            'estonada' => 69.90,
            'dry_sport' => 79.90
        ];

        return $basePrices[$quality] ?? 59.90;
    }
}
