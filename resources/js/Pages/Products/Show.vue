<template>
  <div class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-8">
      
      <!-- Breadcrumb -->
      <nav class="flex mb-8" aria-label="Breadcrumb">
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
          <li class="inline-flex items-center">
            <span class="text-gray-500">{{ product.category.name }}</span>
          </li>
          <li>
            <span class="text-gray-400 mx-2">/</span>
          </li>
          <li class="text-gray-900 font-medium" aria-current="page">
            {{ product.name }}
          </li>
        </ol>
      </nav>

      <div class="grid lg:grid-cols-2 gap-12 mb-16">
        
        <!-- Galeria de Imagens -->
        <div class="space-y-4">
          <!-- Imagem Principal -->
          <div class="bg-white rounded-lg overflow-hidden shadow-sm">
            <img 
              :src="selectedImage || product.primary_image_url || '/images/placeholder.jpg'"
              :alt="product.name"
              class="w-full h-96 object-cover"
            />
          </div>
          
          <!-- Thumbnails -->
          <div v-if="product.product_images && product.product_images.length > 1" class="grid grid-cols-4 gap-2">
            <button 
              v-for="image in product.product_images"
              :key="image.id"
              @click="selectedImage = image.image_url"
              :class="[
                'border-2 rounded-lg overflow-hidden transition-all',
                selectedImage === image.image_url || (!selectedImage && image.is_primary) 
                  ? 'border-red-500' 
                  : 'border-gray-200 hover:border-gray-400'
              ]"
            >
              <img 
                :src="image.image_url"
                :alt="product.name"
                class="w-full h-20 object-cover"
              />
            </button>
          </div>
        </div>

        <!-- Informações do Produto -->
        <div class="space-y-6">
          
          <!-- Título e Preço -->
          <div>
            <div class="flex items-center space-x-2 mb-2">
              <span class="bg-black text-white text-xs px-2 py-1 rounded-full">
                {{ formatQuality(product.quality_line) }}
              </span>
              <span v-if="product.is_featured" class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                ⭐ Destaque
              </span>
            </div>
            
            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
            
            <!-- Preço -->
            <div class="mb-6">
              <div v-if="currentPrice !== product.price" class="flex items-center space-x-3">
                <span class="text-3xl font-bold text-red-600">{{ formatPrice(currentPrice) }}</span>
                <span class="text-lg text-gray-500 line-through">{{ formatPrice(product.price) }}</span>
                <span class="bg-red-100 text-red-800 text-sm px-2 py-1 rounded">
                  Economia de {{ formatPrice(product.price - currentPrice) }}
                </span>
              </div>
              <div v-else>
                <span class="text-3xl font-bold text-gray-900">{{ formatPrice(currentPrice) }}</span>
              </div>
              <p class="text-gray-600 mt-2">ou 12x de {{ formatPrice(currentPrice / 12) }} sem juros</p>
              <p class="text-sm text-green-600">💳 Pix à vista com 5% de desconto: {{ formatPrice(currentPrice * 0.95) }}</p>
            </div>
          </div>

          <!-- Descrição da Qualidade -->
          <div class="bg-gray-100 p-4 rounded-lg">
            <h3 class="font-semibold text-lg mb-2">Sobre a Linha {{ formatQuality(product.quality_line) }}</h3>
            <p class="text-gray-700">{{ qualityDescription }}</p>
          </div>

          <!-- Seleção de Variações -->
          <div class="space-y-4">
            
            <!-- Seleção de Cor -->
            <div v-if="availableColors.length > 0">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Cor: <span class="font-normal text-gray-900">{{ selectedColor || 'Selecione' }}</span>
              </label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="color in availableColors"
                  :key="color"
                  @click="selectColor(color)"
                  :class="[
                    'px-4 py-2 border-2 rounded-md transition-all',
                    selectedColor === color
                      ? 'border-red-500 bg-red-50 text-red-700'
                      : 'border-gray-300 hover:border-gray-400 text-gray-700'
                  ]"
                >
                  {{ color }}
                </button>
              </div>
            </div>

            <!-- Seleção de Tamanho -->
            <div v-if="availableSizes.length > 0">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Tamanho: <span class="font-normal text-gray-900">{{ selectedSize || 'Selecione' }}</span>
              </label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="size in availableSizes"
                  :key="size"
                  @click="selectSize(size)"
                  :class="[
                    'px-4 py-2 border-2 rounded-md transition-all min-w-[3rem]',
                    selectedSize === size
                      ? 'border-red-500 bg-red-50 text-red-700'
                      : 'border-gray-300 hover:border-gray-400 text-gray-700'
                  ]"
                >
                  {{ size }}
                </button>
              </div>
            </div>

            <!-- Quantidade -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Quantidade</label>
              <div class="flex items-center space-x-3">
                <button 
                  @click="decreaseQuantity"
                  :disabled="quantity <= 1"
                  class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  -
                </button>
                <span class="text-lg font-medium px-4">{{ quantity }}</span>
                <button 
                  @click="increaseQuantity"
                  class="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  +
                </button>
              </div>
            </div>
          </div>

          <!-- Botões de Ação -->
          <div class="space-y-3">
            <button 
              @click="addToCart"
              :disabled="!canAddToCart"
              class="w-full bg-red-600 text-white py-4 px-6 rounded-md text-lg font-medium hover:bg-red-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
            >
              <span v-if="canAddToCart">
                Adicionar ao Carrinho - {{ formatPrice(currentPrice * quantity) }}
              </span>
              <span v-else>
                Selecione {{ missingSelections.join(' e ') }}
              </span>
            </button>
            
            <button 
              @click="buyNow"
              :disabled="!canAddToCart"
              class="w-full bg-green-600 text-white py-3 px-6 rounded-md font-medium hover:bg-green-700 disabled:bg-gray-400 disabled:cursor-not-allowed transition-colors"
            >
              Comprar Agora
            </button>
          </div>

          <!-- Informações Adicionais -->
          <div class="border-t pt-6 space-y-4">
            <div class="flex items-center space-x-2 text-sm text-gray-600">
              <span>🚚</span>
              <span>Frete grátis para compras acima de R$ 200,00</span>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-600">
              <span>↩️</span>
              <span>Devolução em até 30 dias</span>
            </div>
            <div class="flex items-center space-x-2 text-sm text-gray-600">
              <span>🏭</span>
              <span>Produção sob demanda em até 7 dias úteis</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Descrição Detalhada -->
      <div class="bg-white rounded-lg shadow-sm p-8 mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Descrição</h2>
        <div class="prose max-w-none text-gray-700">
          <p>{{ product.description }}</p>
        </div>
      </div>

      <!-- Produtos Relacionados -->
      <div v-if="relatedProducts.length > 0" class="mb-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-8">Produtos Relacionados</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="relatedProduct in relatedProducts"
            :key="relatedProduct.id"
            class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 group"
          >
            <div class="relative h-48 bg-gray-100 overflow-hidden">
              <img 
                :src="relatedProduct.primary_image_url || '/images/placeholder.jpg'"
                :alt="relatedProduct.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div class="absolute top-2 left-2">
                <span class="bg-black text-white text-xs px-2 py-1 rounded-full">
                  {{ formatQuality(relatedProduct.quality_line) }}
                </span>
              </div>
            </div>
            <div class="p-4">
              <h3 class="font-semibold mb-2 line-clamp-2">{{ relatedProduct.name }}</h3>
              <p class="text-lg font-bold text-gray-900 mb-3">{{ formatPrice(relatedProduct.price) }}</p>
              <Link 
                :href="route('products.show', relatedProduct.slug)"
                class="w-full bg-red-600 text-white text-center py-2 px-4 rounded-md hover:bg-red-700 transition-colors block"
              >
                Ver Produto
              </Link>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'

// Props do componente
const props = defineProps({
  product: Object,
  relatedProducts: Array,
  availableColors: Array,
  availableSizes: Array,
  variantPrices: Object,
  qualityDescription: String
})

// Estado reativo
const selectedColor = ref('')
const selectedSize = ref('')
const quantity = ref(1)
const selectedImage = ref('')

// Preço atual baseado nas variações selecionadas
const currentPrice = computed(() => {
  if (selectedSize.value && selectedColor.value && props.variantPrices[selectedSize.value] && props.variantPrices[selectedSize.value][selectedColor.value]) {
    return props.variantPrices[selectedSize.value][selectedColor.value].price
  }
  return props.product.price
})

// Verificar se pode adicionar ao carrinho
const canAddToCart = computed(() => {
  const needsColor = props.availableColors.length > 0
  const needsSize = props.availableSizes.length > 0
  
  return (!needsColor || selectedColor.value) && (!needsSize || selectedSize.value)
})

// Seleções faltantes
const missingSelections = computed(() => {
  const missing = []
  if (props.availableColors.length > 0 && !selectedColor.value) missing.push('cor')
  if (props.availableSizes.length > 0 && !selectedSize.value) missing.push('tamanho')
  return missing
})

// Métodos
const selectColor = (color) => {
  selectedColor.value = color
}

const selectSize = (size) => {
  selectedSize.value = size
}

const increaseQuantity = () => {
  quantity.value++
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const addToCart = async () => {
  if (!canAddToCart.value) return
  
  const cartData = {
    product_id: props.product.id,
    size: selectedSize.value,
    color: selectedColor.value,
    quantity: quantity.value
  }
  
  try {
    const response = await fetch(route('cart.add'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(cartData)
    })
    
    if (response.ok) {
      // Mostrar mensagem de sucesso
      alert(`✅ Produto adicionado ao carrinho!\n\n${props.product.name}\nTamanho: ${selectedSize.value}\nCor: ${selectedColor.value}\nQuantidade: ${quantity.value}`)
      
      // Resetar seleções
      selectedColor.value = ''
      selectedSize.value = ''
      quantity.value = 1
      
      // Opcional: Atualizar contador do carrinho no header
      window.dispatchEvent(new CustomEvent('cart-updated'))
    } else {
      const errorData = await response.json()
      alert('Erro ao adicionar produto: ' + (errorData.message || 'Erro desconhecido'))
    }
  } catch (error) {
    console.error('Erro ao adicionar ao carrinho:', error)
    alert('Erro ao adicionar produto ao carrinho. Tente novamente.')
  }
}

const buyNow = async () => {
  if (!canAddToCart.value) return
  
  const cartData = {
    product_id: props.product.id,
    size: selectedSize.value,
    color: selectedColor.value,
    quantity: quantity.value
  }
  
  try {
    const response = await fetch(route('cart.add'), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify(cartData)
    })
    
    if (response.ok) {
      // Redirecionar para o carrinho
      router.visit(route('cart.index'))
    } else {
      const errorData = await response.json()
      alert('Erro ao adicionar produto: ' + (errorData.message || 'Erro desconhecido'))
    }
  } catch (error) {
    console.error('Erro ao adicionar ao carrinho:', error)
    alert('Erro ao adicionar produto ao carrinho. Tente novamente.')
  }
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

const formatPrice = (price) => {
  return 'R$ ' + parseFloat(price).toLocaleString('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

// Inicializar primeira imagem se houver múltiplas
if (props.product.product_images && props.product.product_images.length > 0) {
  const primaryImage = props.product.product_images.find(img => img.is_primary)
  selectedImage.value = primaryImage ? primaryImage.image_url : props.product.product_images[0].image_url
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.prose {
  line-height: 1.6;
}
</style>
