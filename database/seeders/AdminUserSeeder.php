<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin
        User::create([
            'name' => 'Admin Vibe Garb',
            'email' => 'admin@vibegarb.com',
            'password' => Hash::make('admin123'),
            'phone' => '(11) 99999-9999',
            'role' => 'admin',
            'address' => 'Rua das Flores, 123',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01234-567',
            'email_verified_at' => now()
        ]);

        // Criar alguns usuários clientes de exemplo
        User::create([
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'password' => Hash::make('123456'),
            'phone' => '(11) 88888-8888',
            'role' => 'customer',
            'address' => 'Av. Paulista, 456',
            'city' => 'São Paulo',
            'state' => 'SP',
            'zip_code' => '01310-100',
            'email_verified_at' => now()
        ]);

        User::create([
            'name' => 'Maria Santos',
            'email' => 'maria@example.com',
            'password' => Hash::make('123456'),
            'phone' => '(21) 77777-7777',
            'role' => 'customer',
            'address' => 'Rua Copacabana, 789',
            'city' => 'Rio de Janeiro',
            'state' => 'RJ',
            'zip_code' => '22070-010',
            'email_verified_at' => now()
        ]);
    }
}
