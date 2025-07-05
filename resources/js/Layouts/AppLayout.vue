<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between items-center">
          <!-- Logo -->
          <div class="flex items-center">
            <h1 class="text-2xl font-bold text-brand-600">Vibe Garb</h1>
          </div>

          <!-- Navigation -->
          <nav class="hidden md:flex space-x-8">
            <a href="#" class="text-gray-700 hover:text-brand-600 transition-colors">Camisetas</a>
            <a href="#" class="text-gray-700 hover:text-brand-600 transition-colors">Sobre</a>
            <a href="#" class="text-gray-700 hover:text-brand-600 transition-colors">Contato</a>
          </nav>

          <!-- Cart & User Actions -->
          <div class="flex items-center space-x-4">
            <button class="relative p-2 text-gray-700 hover:text-brand-600 transition-colors">
              <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6H19"></path>
              </svg>
              <span class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
            </button>
            <button class="btn-primary">Entrar</button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main>
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-16">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <div>
            <h3 class="text-lg font-semibold mb-4">Vibe Garb</h3>
            <p class="text-gray-300">Camisetas únicas que expressam sua personalidade.</p>
          </div>
          <div>
            <h4 class="font-semibold mb-4">Produtos</h4>
            <ul class="space-y-2 text-gray-300">
              <li><a href="#" class="hover:text-white transition-colors">Camisetas Masculinas</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Camisetas Femininas</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Camisetas Unissex</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-semibold mb-4">Atendimento</h4>
            <ul class="space-y-2 text-gray-300">
              <li><a href="#" class="hover:text-white transition-colors">Central de Ajuda</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Trocas e Devoluções</a></li>
              <li><a href="#" class="hover:text-white transition-colors">Frete Grátis</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-semibold mb-4">Contato</h4>
            <ul class="space-y-2 text-gray-300">
              <li>contato@vibegarb.com.br</li>
              <li>(11) 9999-9999</li>
              <li>São Paulo, SP</li>
            </ul>
          </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-300">
          <p>&copy; 2025 Vibe Garb. Todos os direitos reservados.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
// Layout logic here
</script> 