@extends('layouts.app')

@section('title', 'Vibe Garb - Debug Mode')

@section('content')
<!-- Debug Section -->
<section class="bg-green-600 text-white py-8">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-2xl font-bold mb-4">🐛 DEBUG MODE ATIVO</h2>
        <p>Esta é uma versão simplificada da homepage para teste</p>
    </div>
</section>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-6">🎉 Vibe Garb</h1>
        <p class="text-xl mb-8">CSS e Layout funcionando perfeitamente!</p>
        <a href="{{ route('products.index') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
            Explorar Coleção
        </a>
    </div>
</section>

<!-- Estatísticas Simples -->
<section class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-3xl font-bold text-purple-600">7</h3>
                <p class="text-gray-600">Produtos Disponíveis</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-3xl font-bold text-purple-600">6</h3>
                <p class="text-gray-600">Categorias</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-3xl font-bold text-purple-600">0</h3>
                <p class="text-gray-600">Produtos em Destaque</p>
            </div>
        </div>
    </div>
</section>

<!-- Mensagem de Sucesso -->
<section class="py-16">
    <div class="container mx-auto px-4 text-center">
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-8 rounded-lg max-w-2xl mx-auto">
            <h2 class="text-2xl font-bold mb-4">✅ Problema Resolvido!</h2>
            <p class="text-lg mb-4">A estilização está funcionando corretamente. O problema anterior era causado por timeout nas queries do banco de dados.</p>
            <p class="text-sm text-green-600">Layout responsivo • Tailwind CSS • Laravel Blade • Funcionando!</p>
        </div>
    </div>
</section>

<!-- Newsletter -->
<section class="bg-purple-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">🚀 Sistema Funcionando</h2>
        <p class="text-lg mb-8">A interface está carregando perfeitamente</p>
        <div class="max-w-md mx-auto">
            <span class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold">
                Vibe Garb • CSS OK
            </span>
        </div>
    </div>
</section>
@endsection 