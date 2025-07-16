<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Banner;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Coleção Verão 2024',
                'description' => 'Descubra as últimas tendências da moda verão com até 50% de desconto em peças selecionadas',
                'image_path' => 'banners/verao-2024.jpg',
                'link' => '/produtos?categoria=verao',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Street Style Urbano',
                'description' => 'Vista-se com atitude! Coleção streetwear com peças exclusivas e limited edition',
                'image_path' => 'banners/street-style.jpg',
                'link' => '/produtos?categoria=streetwear',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Frete Grátis',
                'description' => 'Frete grátis em compras acima de R$ 199,00 para todo o Brasil',
                'image_path' => 'banners/frete-gratis.jpg',
                'link' => '/produtos',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Acessórios em Destaque',
                'description' => 'Complete seu look com nossa linha exclusiva de acessórios e calçados',
                'image_path' => 'banners/acessorios.jpg',
                'link' => '/produtos?categoria=acessorios',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Black Friday Preview',
                'description' => 'Descontos imperdíveis chegando! Cadastre-se e seja o primeiro a saber',
                'image_path' => 'banners/black-friday.jpg',
                'link' => '/newsletter',
                'order' => 5,
                'is_active' => false, // Inativo para demonstrar controle de status
            ],
        ];

        foreach ($banners as $banner) {
            Banner::create($banner);
        }
    }
} 