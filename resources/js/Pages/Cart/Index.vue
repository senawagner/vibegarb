<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header da página -->
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Meu Carrinho</h1>
            <p class="text-gray-600 mt-2">{{ itemCount }} {{ itemCount === 1 ? 'item' : 'itens' }} adicionado{{ itemCount === 1 ? '' : 's' }}</p>
          </div>
          
          <!-- Breadcrumb -->
          <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
              <li class="inline-flex items-center">
                <Link :href="route('home')" class="text-gray-700 hover:text-red-600">Home</Link>
              </li>
              <li>
                <span class="text-gray-400 mx-2">/</span>
              </li>
              <li class="inline-flex items-center">
                <Link :href="route('products.index')" class="text-gray-700 hover:text-red-600">Produtos</Link>
              </li>
              <li>
                <span class="text-gray-400 mx-2">/</span>
              </li>
              <li class="text-gray-900 font-medium" aria-current="page">
                Carrinho
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>

    <div class="container mx-auto px-4 py-8">
      <!-- Carrinho vazio -->
      <div v-if="itemCount === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
        <div class="text-6xl mb-4">🛒</div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Seu carrinho está vazio</h3>
        <p class="text-gray-600 mb-6">Que tal adicionar alguns produtos incríveis?</p>
        <Link 
          :href="route('products.index')" 
          class="bg-red-600 text-white py-3 px-6 rounded-md hover:bg-red-700 transition-colors inline-block"
        >
          Continuar Comprando
        </Link>
      </div>

      <!-- Carrinho com itens -->
      <div v-else class="grid lg:grid-cols-3 gap-8">
        
        <!-- Lista de itens (2/3 da tela) -->
        <div class="lg:col-span-2 space-y-4">
          
          <!-- Ações do carrinho -->
          <div class="bg-white rounded-lg shadow-sm p-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <Link 
                  :href="route('products.index')" 
                  class="text-red-600 hover:text-red-700 font-medium"
                >
                  ← Continuar Comprando
                </Link>
              </div>
              
              <button 
                @click="clearCart"
                :disabled="loading"
                class="text-gray-500 hover:text-red-600 text-sm font-medium transition-colors"
              >
                Limpar Carrinho
              </button>
            </div>
          </div>

          <!-- Alerta de frete grátis -->
          <div v-if="!cartSummary.has_free_shipping && cartSummary.free_shipping_remaining > 0" 
               class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
              <span class="text-green-600 mr-2">🚚</span>
              <p class="text-green-800 text-sm">
                Faltam <strong>{{ cartSummary.formatted_free_shipping_remaining }}</strong> para ganhar <strong>frete grátis!</strong>
              </p>
            </div>
          </div>

          <div v-else-if="cartSummary.has_free_shipping" 
               class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
              <span class="text-green-600 mr-2">✅</span>
              <p class="text-green-800 text-sm font-medium">
                Parabéns! Você ganhou <strong>frete grátis</strong>!
              </p>
            </div>
          </div>
          
          <!-- Itens do carrinho -->
          <div class="space-y-4">
            <div 
              v-for="item in cartItems" 
              :key="item.key"
              class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow"
            >
              <div class="grid md:grid-cols-6 gap-4 items-center">
                
                <!-- Imagem do produto -->
                <div class="md:col-span-1">
                  <Link :href="item.product_url">
                    <img 
                      :src="item.image || '/images/placeholder.jpg'"
                      :alt="item.name"
                      class="w-20 h-20 object-cover rounded-md hover:opacity-75 transition-opacity"
                    />
                  </Link>
                </div>

                <!-- Informações do produto -->
                <div class="md:col-span-2">
                  <Link :href="item.product_url" class="block hover:text-red-600 transition-colors">
                    <h3 class="font-semibold text-lg mb-1">{{ item.name }}</h3>
                  </Link>
                  
                  <div class="text-sm text-gray-600 space-y-1">
                    <p><span class="font-medium">Qualidade:</span> {{ formatQuality(item.quality_line) }}</p>
                    <p><span class="font-medium">Tamanho:</span> {{ item.size }}</p>
                    <p><span class="font-medium">Cor:</span> {{ item.color }}</p>
                  </div>
                  
                  <p class="text-xs text-gray-500 mt-2">{{ item.quality_description }}</p>
                </div>

                <!-- Controle de quantidade -->
                <div class="md:col-span-1">
                  <div class="flex items-center justify-center space-x-2">
                    <button 
                      @click="updateQuantity(item.key, item.quantity - 1)"
                      :disabled="item.quantity <= 1 || loading"
                      class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      -
                    </button>
                    
                    <span class="text-lg font-medium w-8 text-center">{{ item.quantity }}</span>
                    
                    <button 
                      @click="updateQuantity(item.key, item.quantity + 1)"
                      :disabled="item.quantity >= 10 || loading"
                      class="w-8 h-8 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                      +
                    </button>
                  </div>
                  
                  <p class="text-center text-xs text-gray-500 mt-1">Máx: 10</p>
                </div>

                <!-- Preço unitário -->
                <div class="md:col-span-1 text-center">
                  <p class="text-sm text-gray-600">Unitário</p>
                  <p class="font-semibold text-lg">{{ item.formatted_unit_price }}</p>
                </div>

                <!-- Total e ações -->
                <div class="md:col-span-1 text-center">
                  <p class="text-sm text-gray-600">Total</p>
                  <p class="font-bold text-lg text-red-600">{{ item.formatted_total }}</p>
                  
                  <button 
                    @click="removeItem(item.key)"
                    :disabled="loading"
                    class="text-xs text-gray-500 hover:text-red-600 mt-2 transition-colors"
                  >
                    Remover
                  </button>
                </div>

              </div>
            </div>
          </div>
        </div>

        <!-- Resumo do pedido (1/3 da tela) -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
            <h3 class="text-xl font-semibold mb-6">Resumo do Pedido</h3>
            
            <!-- Resumo de valores -->
            <div class="space-y-4 mb-6">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Subtotal ({{ cartSummary.total_items }} {{ cartSummary.total_items === 1 ? 'item' : 'itens' }})</span>
                <span class="font-medium">{{ cartSummary.formatted_subtotal }}</span>
              </div>
              
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Frete</span>
                <span class="font-medium" :class="cartSummary.has_free_shipping ? 'text-green-600' : ''">
                  {{ cartSummary.formatted_shipping }}
                </span>
              </div>
              
              <div class="border-t pt-4">
                <div class="flex justify-between text-lg font-bold">
                  <span>Total</span>
                  <span>{{ cartSummary.formatted_total }}</span>
                </div>
              </div>
              
              <!-- Desconto PIX -->
              <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                <div class="flex items-center justify-between text-sm">
                  <div class="flex items-center">
                    <span class="text-green-600 mr-2">💳</span>
                    <span class="text-green-800 font-medium">No PIX</span>
                  </div>
                  <span class="text-green-800 font-bold">{{ cartSummary.formatted_pix_total }}</span>
                </div>
                <p class="text-xs text-green-700 mt-1">Economia de 5% no pagamento via PIX</p>
              </div>
            </div>

            <!-- Botões de ação -->
            <div class="space-y-3">
              <button 
                @click="goToCheckout"
                :disabled="loading || itemCount === 0"
                class="w-full bg-red-600 text-white py-4 px-6 rounded-md text-lg font-medium hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
              >
                <span v-if="!loading">Finalizar Compra</span>
                <span v-else>Processando...</span>
              </button>
              
              <Link 
                :href="route('products.index')" 
                class="w-full bg-gray-200 text-gray-700 py-3 px-6 rounded-md font-medium hover:bg-gray-300 transition-colors block text-center"
              >
                Continuar Comprando
              </Link>
            </div>

            <!-- Informações de segurança -->
            <div class="border-t pt-6 mt-6 space-y-3">
              <div class="flex items-center text-sm text-gray-600">
                <span class="mr-2">🔒</span>
                <span>Compra 100% segura</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <span class="mr-2">🚚</span>
                <span>Entrega em todo o Brasil</span>
              </div>
              <div class="flex items-center text-sm text-gray-600">
                <span class="mr-2">↩️</span>
                <span>Troca em até 30 dias</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props do componente
const props = defineProps({
  cartItems: Array,
  cartSummary: Object,
  itemCount: Number
})

// Estado reativo
const loading = ref(false)

// Métodos do carrinho
const updateQuantity = async (itemKey, newQuantity) => {
  if (newQuantity < 1 || newQuantity > 10 || loading.value) return
  
  loading.value = true
  
  try {
    const response = await fetch(route('cart.update', itemKey), {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ quantity: newQuantity })
    })
    
    const data = await response.json()
    
    if (data.success) {
      // Recarregar página para atualizar dados
      router.reload({ only: ['cartItems', 'cartSummary', 'itemCount'] })
    } else {
      alert('Erro ao atualizar quantidade: ' + data.message)
    }
  } catch (error) {
    console.error('Erro ao atualizar quantidade:', error)
    alert('Erro ao atualizar quantidade')
  } finally {
    loading.value = false
  }
}

const removeItem = async (itemKey) => {
  if (!confirm('Deseja remover este item do carrinho?')) return
  
  loading.value = true
  
  try {
    const response = await fetch(route('cart.remove', itemKey), {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    
    const data = await response.json()
    
    if (data.success) {
      // Recarregar página para atualizar dados
      router.reload({ only: ['cartItems', 'cartSummary', 'itemCount'] })
    } else {
      alert('Erro ao remover item: ' + data.message)
    }
  } catch (error) {
    console.error('Erro ao remover item:', error)
    alert('Erro ao remover item')
  } finally {
    loading.value = false
  }
}

const clearCart = async () => {
  if (!confirm('Deseja limpar todo o carrinho?')) return
  
  loading.value = true
  
  try {
    const response = await fetch(route('cart.clear'), {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    
    const data = await response.json()
    
    if (data.success) {
      // Recarregar página para atualizar dados
      router.reload({ only: ['cartItems', 'cartSummary', 'itemCount'] })
    } else {
      alert('Erro ao limpar carrinho: ' + data.message)
    }
  } catch (error) {
    console.error('Erro ao limpar carrinho:', error)
    alert('Erro ao limpar carrinho')
  } finally {
    loading.value = false
  }
}

const goToCheckout = () => {
  // Por enquanto alerta, será implementado na Sprint 4
  alert(`Checkout iniciado!\n\nTotal: ${props.cartSummary.formatted_total}\nItens: ${props.itemCount}\n\n(Será implementado na Sprint 4 - Checkout)`)
}

// Formatação
const formatQuality = (quality) => {
  const qualities = {
    'classic': 'Classic',
    'quality': 'Quality',
    'prime': 'Prime',
    'pima': 'Pima',
    'estonada': 'Estonada',
    'dry_sport': 'Dry Sport'
  }
  return qualities[quality] || quality
}
</script>

<style scoped>
/* Transições suaves para loading states */
.transition-opacity {
  transition: opacity 0.2s ease-in-out;
}

/* Estilos para inputs desabilitados */
button:disabled {
  transition: all 0.2s ease-in-out;
}
</style>
