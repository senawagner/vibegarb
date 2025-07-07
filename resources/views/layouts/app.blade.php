<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vibe Garb') }} - @yield('title', 'Loja Online')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50">
        {{-- Container para Notificações Dinâmicas --}}
        <div id="notification" class="hidden fixed top-5 left-1/2 -translate-x-1/2 z-[100] px-6 py-3 rounded-lg text-white shadow-lg transition-all duration-500 transform -translate-y-full opacity-0">
            <p id="notification-message"></p>
        </div>

        <div class="min-h-screen bg-gray-100">
            <!-- Navigation -->
            <nav class="bg-white shadow-lg sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="{{ route('home') }}" class="flex items-center">
                                <span class="text-2xl font-bold text-purple-600">👕 Vibe Garb</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden md:flex items-center space-x-8">
                            <a href="{{ route('home') }}" class="text-gray-900 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('home') ? 'text-purple-600 bg-purple-50' : '' }}">
                                Início
                            </a>
                            <a href="{{ route('products.index') }}" class="text-gray-900 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('products.*') ? 'text-purple-600 bg-purple-50' : '' }}">
                                Produtos
                            </a>
                            
                            <!-- Categories Dropdown -->
                            <div class="relative group">
                                <button class="text-gray-900 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                    Categorias
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                    <!-- Categories will be loaded here -->
                                    <a href="{{ route('products.index') }}" 
                                       class="block px-4 py-2 text-sm text-purple-600 hover:bg-purple-50 font-medium">
                                        Ver Todas as Categorias
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side -->
                        <div class="hidden md:flex items-center space-x-4">
                            <!-- Search -->
                            <div class="relative">
                                <input type="text" 
                                       placeholder="Buscar produtos..." 
                                       class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                       id="search-input">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Cart -->
                            <a href="{{ route('cart.index') }}" class="relative flex items-center text-gray-700 hover:text-purple-600">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13h10m-4 8a2 2 0 100-4 2 2 0 000 4zm-4 0a2 2 0 100-4 2 2 0 000 4z"></path>
                                </svg>
                                <span class="ml-1">Carrinho</span>
                                @php 
                                    // Usar nosso próprio CartService para obter a contagem de itens
                                    $cartService = new \App\Services\CartService(app('session'));
                                    $cartItemCount = $cartService->getTotalQuantity();
                                @endphp
                                <span id="cart-count" 
                                      class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center {{ $cartItemCount > 0 ? '' : 'hidden' }}">
                                    {{ $cartItemCount }}
                                </span>
                            </a>

                            <!-- Authentication Links -->
                            @auth
                                <div class="relative group">
                                    <button class="flex items-center text-gray-700 hover:text-purple-600">
                                        <svg class="h-6 w-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ Auth::user()->name }}
                                    </button>
                                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                                        @if(Auth::user()->email === 'admin@vibegarb.com')
                                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-purple-700 hover:bg-purple-50 font-medium">🔧 Painel Admin</a>
                                            <hr class="my-1">
                                        @endif
                                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">Dashboard</a>
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">Perfil</a>
                                        <hr class="my-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">
                                                Sair
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600">Login</a>
                                <a href="{{ route('register') }}" class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700">Cadastrar</a>
                            @endauth
                        </div>

                        <!-- Mobile menu button -->
                        <div class="md:hidden">
                            <button type="button" class="text-gray-700 hover:text-purple-600" id="mobile-menu-button">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div class="md:hidden" id="mobile-menu" style="display: none;">
                    <div class="px-2 pt-2 pb-3 space-y-1 bg-gray-50">
                        <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-900 hover:text-purple-600">Início</a>
                        <a href="{{ route('products.index') }}" class="block px-3 py-2 text-gray-900 hover:text-purple-600">Produtos</a>
                        <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-gray-900 hover:text-purple-600">Carrinho</a>
                        
                        @auth
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-900 hover:text-purple-600">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-900 hover:text-purple-600">Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-gray-900 hover:text-purple-600">Sair</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-900 hover:text-purple-600">Login</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-900 hover:text-purple-600">Cadastrar</a>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative m-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-gray-900 text-white py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Vibe Garb</h3>
                            <p class="text-gray-400">Sua vibe, seu style. As melhores camisetas com qualidade premium.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Categorias</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="{{ route('products.index') }}" class="hover:text-white">Todas as Categorias</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Ajuda</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li><a href="#" class="hover:text-white">FAQ</a></li>
                                <li><a href="#" class="hover:text-white">Frete e Entrega</a></li>
                                <li><a href="#" class="hover:text-white">Trocas e Devoluções</a></li>
                                <li><a href="#" class="hover:text-white">Contato</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Redes Sociais</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-400 hover:text-white">Instagram</a>
                                <a href="#" class="text-gray-400 hover:text-white">Facebook</a>
                                <a href="#" class="text-gray-400 hover:text-white">Twitter</a>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                        <p>&copy; {{ date('Y') }} Vibe Garb. Todos os direitos reservados.</p>
                    </div>
                </div>
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>
