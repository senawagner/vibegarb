<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Painel Admin') - Vibe Garb</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="/build/assets/app-CqPnkobs.css">
        <script src="/build/assets/app-i7Ae5HK0.js" defer></script>

        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <div class="w-64 bg-gray-800 shadow-lg">
                <div class="p-6">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <span class="text-xl font-bold text-white">👕 Vibe Garb</span>
                    </div>
                    <div class="text-sm text-gray-300 mt-1">Painel Administrativo</div>
                </div>

                <!-- Navigation -->
                <nav class="mt-6">
                    <div class="px-6 py-2">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Menu Principal</div>
                    </div>

                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white border-r-4 border-purple-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>

                    <!-- Produtos -->
                    <a href="{{ route('admin.products') }}" 
                       class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('admin.products*') ? 'bg-gray-700 text-white border-r-4 border-purple-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Produtos
                    </a>

                    <!-- Categorias -->
                    <a href="{{ route('admin.categories') }}" 
                       class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('admin.categories*') ? 'bg-gray-700 text-white border-r-4 border-purple-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Categorias
                    </a>

                    <!-- Pedidos -->
                    <a href="{{ route('admin.orders') }}" 
                       class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('admin.orders*') ? 'bg-gray-700 text-white border-r-4 border-purple-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Pedidos
                    </a>

                    <!-- Dropshipping -->
                    <a href="{{ route('admin.dropshipping.dashboard') }}" 
                       class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition {{ request()->routeIs('admin.dropshipping*') ? 'bg-gray-700 text-white border-r-4 border-purple-500' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Dropshipping
                    </a>

                    <!-- Divider -->
                    <div class="px-6 py-2 mt-6">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Loja</div>
                    </div>

                    <!-- Ver Loja -->
                    <a href="{{ route('home') }}" target="_blank"
                       class="flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Ver Loja
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center px-6 py-3 text-gray-300 hover:bg-gray-700 hover:text-white transition">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Sair
                        </button>
                    </form>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Header -->
                <header class="bg-white shadow-sm border-b">
                    <div class="flex items-center justify-between px-6 py-4">
                        <h1 class="text-2xl font-semibold text-gray-900">@yield('page-title', 'Dashboard')</h1>
                        
                        <div class="flex items-center space-x-4">
                            <!-- User Info -->
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-gray-600">Olá, {{ Auth::user()->name }}</span>
                                <div class="w-8 h-8 bg-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm font-medium">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-auto bg-gray-50">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-6 mt-4 rounded" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html> 