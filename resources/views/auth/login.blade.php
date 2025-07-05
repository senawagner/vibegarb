<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Vibe Garb</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="/build/assets/app-sUSOkK7e.css">
    <script src="/build/assets/app-D1E387N5.js" defer></script>
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 to-indigo-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo e Título -->
            <div>
                <div class="text-center">
                    <h1 class="text-4xl font-bold text-purple-600 mb-2">🎯 Vibe Garb</h1>
                    <h2 class="text-2xl font-semibold text-gray-900">Bem-vindo de volta!</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Entre na sua conta para continuar
                    </p>
                </div>
            </div>

            <!-- Card do Formulário -->
            <div class="bg-white py-8 px-6 shadow-xl rounded-lg">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            E-mail
                        </label>
                        <div class="mt-1">
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email" 
                                   required 
                                   value="{{ old('email') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Senha
                        </label>
                        <div class="mt-1">
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   autocomplete="current-password" 
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm @error('password') border-red-500 @enderror">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" 
                                   name="remember" 
                                   type="checkbox"
                                   class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                Lembrar de mim
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-purple-600 hover:text-purple-500">
                                    Esqueceu sua senha?
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150">
                            Entrar
                        </button>
                    </div>
                </form>

                <!-- Divider -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Ou</span>
                        </div>
                    </div>
                </div>

                <!-- Register Link -->
                <div class="mt-6">
                    <a href="{{ route('register') }}" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-150">
                        Criar nova conta
                    </a>
                </div>

                <!-- Admin Info -->
                <div class="mt-6 text-center bg-gray-50 rounded p-3">
                    <p class="text-xs text-gray-600 font-medium">
                        🔑 Conta Admin de Teste:
                    </p>
                    <p class="text-xs text-gray-700 mt-1">
                        admin@vibegarb.com / admin123
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-sm text-gray-600">
                © 2025 Vibe Garb. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html> 