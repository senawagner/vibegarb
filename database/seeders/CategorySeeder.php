<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Camisetas Básicas',
                'slug' => 'camisetas-basicas',
                'description' => 'Camisetas básicas para o dia a dia, confortáveis e versáteis.',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Camisetas Estampadas',
                'slug' => 'camisetas-estampadas',
                'description' => 'Camisetas com estampas exclusivas e designs únicos.',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Camisetas Oversized',
                'slug' => 'camisetas-oversized',
                'description' => 'Camisetas com modelagem oversized para um look moderno.',
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Camisetas Longline',
                'slug' => 'camisetas-longline',
                'description' => 'Camisetas com corte alongado para um visual despojado.',
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Regatas',
                'slug' => 'regatas',
                'description' => 'Regatas masculinas e femininas para os dias quentes.',
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Edição Limitada',
                'slug' => 'edicao-limitada',
                'description' => 'Peças exclusivas em quantidade limitada.',
                'is_active' => true,
                'sort_order' => 6
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
