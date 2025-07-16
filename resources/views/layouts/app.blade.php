<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Vibe Garb') }} - @yield('title', 'Loja Online')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50">
        {{-- Container para Notificações Dinâmicas --}}
        <div id="notification" class="hidden fixed top-5 left-1/2 -translate-x-1/2 z-[100] px-6 py-3 rounded-lg text-white shadow-lg transition-all duration-500 transform -translate-y-full opacity-0">
            <p id="notification-message"></p>
        </div>

        <div class="min-h-screen bg-gray-50">
            <!-- Navigation -->
            <nav class="bg-white shadow-lg sticky top-0 z-50">
                <!-- Linha Superior - Sempre Visível (Altura Dobrada) -->
                <div class="border-b border-gray-200 relative z-10">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between items-center h-48">
                            <!-- Logo -->
                            <div class="flex items-center">
                                <a href="{{ route('home') }}" class="flex items-center">
                                    <div class="text-4xl font-bold text-gray-900 uppercase tracking-wider">
                                        VIBEGARB
                                    </div>
                                </a>
                            </div>

                            <!-- Campo de Busca Central -->
                            <div class="flex-1 max-w-2xl mx-12">
                                <div class="relative">
                                    <input type="text" 
                                           placeholder="O QUE VOCÊ PROCURA?" 
                                           class="w-full pl-6 pr-16 py-5 bg-gray-100 border-0 rounded-full text-lg text-gray-700 placeholder-gray-500 focus:ring-2 focus:ring-gray-300 focus:bg-white transition-all duration-200"
                                           id="search-input">
                                    <div class="absolute inset-y-0 right-0 pr-6 flex items-center">
                                        <button class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side - Conta e Carrinho -->
                            <div class="flex items-center space-x-10">
                                <!-- SAC -->
                                <div class="hidden md:flex flex-col items-center text-gray-700 hover:text-gray-900 transition-colors duration-200">
                                    <svg class="h-7 w-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.001 8.001 0 01-7.29-4.678l-.38-.76L3 16l1.562-2.438.38-.76A8 8 0 0121 12z"></path>
                                    </svg>
                                    <span class="text-sm font-medium">SAC</span>
                                </div>

                                <!-- Minha Conta -->
                                @auth
                                    <div class="relative group">
                                        <button class="flex flex-col items-center text-gray-700 hover:text-gray-900 transition-colors duration-200">
                                            <svg class="h-7 w-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span class="text-sm font-medium">MINHA CONTA</span>
                                        </button>
                                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-20">
                                            @if(Auth::user()->email === 'admin@vibegarb.com')
                                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 font-medium">🔧 Painel Admin</a>
                                                <hr class="border-gray-200">
                                            @endif
                                            <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">Perfil</a>
                                            <hr class="border-gray-200">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="block w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                                    Sair
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('login') }}" class="flex flex-col items-center text-gray-700 hover:text-gray-900 transition-colors duration-200">
                                        <svg class="h-7 w-7 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-sm font-medium">MINHA CONTA</span>
                                    </a>
                                @endauth

                                <!-- Carrinho -->
                                <a href="{{ route('cart.index') }}" class="relative flex flex-col items-center text-gray-700 hover:text-gray-900 transition-colors duration-200">
                                    <div class="relative">
                                        <svg class="h-8 w-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z"></path>
                                        </svg>
                                        @php 
                                            $cartService = new \App\Services\CartService(app('session'));
                                            $cartItemCount = $cartService->getTotalQuantity();
                                        @endphp
                                        <span id="cart-count" 
                                              class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-6 w-6 flex items-center justify-center font-semibold {{ $cartItemCount > 0 ? '' : 'hidden' }}">
                                            {{ $cartItemCount }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium">{{ $cartItemCount }}</span>
                                </a>

                                <!-- Mobile menu button -->
                                <div class="md:hidden">
                                    <button type="button" class="text-gray-700 hover:text-gray-900 transition-colors duration-200" id="mobile-menu-button">
                                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Linha Inferior - Menu de Produtos (Desliza para trás) -->
                <div id="product-menu" class="bg-white border-b border-gray-200 relative z-5 transition-transform duration-300 ease-in-out">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-center items-center h-14 space-x-8">
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 px-4 py-2 text-sm font-medium uppercase tracking-wide transition-colors duration-200 {{ request()->routeIs('products.*') ? 'text-black border-b-2 border-black' : '' }}">
                                CAMISETA TECH MODAL
                            </a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 px-4 py-2 text-sm font-medium uppercase tracking-wide transition-colors duration-200">
                                CAMISETAS STRONG - MALHA PESADA
                            </a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 px-4 py-2 text-sm font-medium uppercase tracking-wide transition-colors duration-200">
                                CAMISETAS BASIC
                            </a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 px-4 py-2 text-sm font-medium uppercase tracking-wide transition-colors duration-200">
                                CAMISAS POLO
                            </a>
                            <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-gray-900 px-4 py-2 text-sm font-medium uppercase tracking-wide transition-colors duration-200">
                                BERMUDAS
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div class="md:hidden" id="mobile-menu" style="display: none;">
                    <div class="px-2 pt-2 pb-3 space-y-1 bg-gray-50 border-t border-gray-200">
                        <a href="{{ route('home') }}" class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">Início</a>
                        <a href="{{ route('products.index') }}" class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">Produtos</a>
                        <a href="{{ route('cart.index') }}" class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">Carrinho</a>
                        
                        @auth
                            @if(Auth::user()->email === 'admin@vibegarb.com')
                                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200">🔧 Painel Admin</a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">Dashboard</a>
                            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">Sair</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 text-gray-700 hover:text-gray-900 hover:bg-gray-100 rounded-md transition-colors duration-200">Login</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 text-gray-700 hover:bg-gray-100 rounded-md transition-colors duration-200">Cadastrar</a>
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
            <footer class="bg-gray-100 border-t border-gray-200 text-gray-700 py-12">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div>
                            <div class="text-xl font-bold text-gray-900 uppercase tracking-wider mb-4">
                                VIBEGARB
                            </div>
                            <p class="text-gray-600">Sua vibe, seu style. As melhores camisetas com qualidade premium.</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 uppercase tracking-wide">Categorias</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li><a href="{{ route('products.index') }}" class="hover:text-gray-900 transition-colors duration-200">Todas as Categorias</a></li>
                                <li><a href="{{ route('products.index') }}" class="hover:text-gray-900 transition-colors duration-200">Tech Modal</a></li>
                                <li><a href="{{ route('products.index') }}" class="hover:text-gray-900 transition-colors duration-200">Strong - Malha Pesada</a></li>
                                <li><a href="{{ route('products.index') }}" class="hover:text-gray-900 transition-colors duration-200">Basic</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 uppercase tracking-wide">Ajuda</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li><a href="#" class="hover:text-gray-900 transition-colors duration-200">FAQ</a></li>
                                <li><a href="#" class="hover:text-gray-900 transition-colors duration-200">Frete e Entrega</a></li>
                                <li><a href="#" class="hover:text-gray-900 transition-colors duration-200">Trocas e Devoluções</a></li>
                                <li><a href="#" class="hover:text-gray-900 transition-colors duration-200">Contato</a></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-900 uppercase tracking-wide">Redes Sociais</h3>
                            <div class="flex space-x-4">
                                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Instagram</a>
                                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Facebook</a>
                                <a href="#" class="text-gray-600 hover:text-gray-900 transition-colors duration-200">Twitter</a>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-300 mt-8 pt-8 text-center text-gray-600">
                        <p>&copy; {{ date('Y') }} Vibe Garb. Todos os direitos reservados.</p>
                    </div>
                </div>
            </footer>
        </div>

        @stack('scripts')
    </body>
</html>
