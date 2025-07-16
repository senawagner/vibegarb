@extends('layouts.app')

@section('title', 'Vibe Garb - Sua Vibe, Seu Style')

@section('content')
<!-- Banner Carousel -->
@if($banners->count() > 0)
<section class="relative overflow-hidden">
    <div class="banner-carousel relative h-96 md:h-[500px]">
        @foreach($banners as $index => $banner)
        <div class="banner-slide absolute inset-0 transition-all duration-500 ease-in-out {{ $index === 0 ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-full' }}" 
             data-slide="{{ $index }}">
            @if($banner->link)
                <a href="{{ $banner->link }}" class="block w-full h-full">
            @endif
            <img src="{{ $banner->image_url }}" 
                 alt="{{ $banner->title }}" 
                 class="w-full h-full object-cover">
            
            @if($banner->title || $banner->description)
            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                <div class="text-center text-white px-4">
                    @if($banner->title)
                        <h2 class="text-3xl md:text-5xl font-bold mb-4">{{ $banner->title }}</h2>
                    @endif
                    @if($banner->description)
                        <p class="text-lg md:text-xl">{{ $banner->description }}</p>
                    @endif
                </div>
            </div>
            @endif
            
            @if($banner->link)
                </a>
            @endif
        </div>
        @endforeach
        
        @if($banners->count() > 1)
        <!-- Navigation Arrows -->
        <button class="banner-prev absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 transition-all duration-200 z-10">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        <button class="banner-next absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-80 hover:bg-opacity-100 rounded-full p-2 transition-all duration-200 z-10">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
        
        <!-- Dots Indicator -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            @foreach($banners as $index => $banner)
            <button class="banner-dot w-3 h-3 rounded-full {{ $index === 0 ? 'bg-white' : 'bg-white bg-opacity-50' }} transition-all duration-200" 
                    data-slide="{{ $index }}"></button>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

<!-- Hero Section -->
<section class="bg-gradient-to-r from-purple-600 to-blue-600 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-6">Vibe Garb</h1>
        <p class="text-xl mb-8">Encontre seu estilo único com nossas camisetas de qualidade premium</p>
        <a href="{{ route('products.index') }}" class="bg-white text-purple-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
            Explorar Coleção
        </a>
    </div>
</section>

<!-- Estatísticas -->
<section class="bg-gray-100 py-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-3xl font-bold text-purple-600">{{ $stats['total_products'] }}</h3>
                <p class="text-gray-600">Produtos Disponíveis</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-3xl font-bold text-purple-600">{{ $stats['total_categories'] }}</h3>
                <p class="text-gray-600">Categorias</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-3xl font-bold text-purple-600">{{ $stats['featured_count'] }}</h3>
                <p class="text-gray-600">Produtos em Destaque</p>
            </div>
        </div>
    </div>
</section>

<!-- Categorias -->
@if($categories->count() > 0)
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Nossas Categorias</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group">
                <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-lg transition duration-300">
                    <div class="text-4xl mb-4">{{ $category->icon ?? '👕' }}</div>
                    <h3 class="font-semibold text-gray-800 group-hover:text-purple-600">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ $category->description ?? 'Explore esta categoria' }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Produtos em Destaque -->
@if($featuredProducts->count() > 0)
<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Produtos em Destaque</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="aspect-square bg-gray-200 relative overflow-hidden">
                        @if($product->primary_image_url)
                            <img src="{{ $product->primary_image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                <span class="text-4xl">👕</span>
                            </div>
                        @endif
                        
                        @if($product->is_featured)
                            <span class="absolute top-2 left-2 bg-purple-600 text-white px-2 py-1 text-xs rounded">Destaque</span>
                        @endif
                    </div>
                </a>
                
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2 hover:text-purple-600">
                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($product->short_description, 60) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xl font-bold text-purple-600">{{ $product->formatted_price }}</span>
                            @if($product->quality_line)
                                <span class="block text-xs text-gray-500">{{ $product->quality_line }}</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-300">
                            Ver Produto
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="{{ route('products.index') }}" 
               class="bg-purple-600 text-white px-8 py-3 rounded-lg hover:bg-purple-700 transition duration-300">
                Ver Todos os Produtos
            </a>
        </div>
    </div>
</section>
@endif

<!-- Produtos Mais Recentes -->
@if($latestProducts->count() > 0)
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Lançamentos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($latestProducts as $product)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
                <a href="{{ route('products.show', $product->slug) }}">
                    <div class="aspect-square bg-gray-200 relative overflow-hidden">
                        @if($product->primary_image_url)
                            <img src="{{ $product->primary_image_url }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500">
                                <span class="text-4xl">👕</span>
                            </div>
                        @endif
                        
                        <span class="absolute top-2 left-2 bg-green-600 text-white px-2 py-1 text-xs rounded">Novo</span>
                    </div>
                </a>
                
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2 hover:text-purple-600">
                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                    </h3>
                    
                    <p class="text-gray-600 text-sm mb-2">{{ Str::limit($product->short_description, 60) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xl font-bold text-purple-600">{{ $product->formatted_price }}</span>
                            @if($product->quality_line)
                                <span class="block text-xs text-gray-500">{{ $product->quality_line }}</span>
                            @endif
                        </div>
                        <a href="{{ route('products.show', $product->slug) }}" 
                           class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-300">
                            Ver Produto
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Newsletter -->
<section class="bg-purple-600 text-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Fique por dentro das novidades</h2>
        <p class="text-lg mb-8">Receba ofertas exclusivas e seja o primeiro a saber dos lançamentos</p>
        <form class="max-w-md mx-auto flex gap-4">
            <input type="email" 
                   placeholder="Seu melhor e-mail" 
                   class="flex-1 px-4 py-3 rounded-lg text-gray-800">
            <button type="submit" 
                    class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                Inscrever
            </button>
        </form>
    </div>
</section>
@endsection 