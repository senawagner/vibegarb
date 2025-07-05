<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header da página -->
    <div class="bg-white shadow-sm">
      <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-900">Catálogo de Produtos</h1>
        <p class="text-gray-600 mt-2">Descubra nossa coleção completa de camisetas e acessórios</p>
      </div>
    </div>

    <div class="container mx-auto px-4 py-8">
      <div class="grid lg:grid-cols-4 gap-8">
        
        <!-- Sidebar de Filtros -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
            <h3 class="font-semibold text-lg mb-6">Filtros</h3>
            
            <!-- Busca -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
              <input 
                v-model="searchForm.search"
                @input="applyFilters"
                type="text" 
                placeholder="Nome do produto..."
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
              />
            </div>

            <!-- Categoria -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
              <select 
                v-model="searchForm.category"
                @change="applyFilters"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
              >
                <option value="">Todas as categorias</option>
                <option v-for="category in categories" :key="category.id" :value="category.slug">
                  {{ category.name }}
                </option>
              </select>
            </div>

            <!-- Linha de Qualidade -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Qualidade</label>
              <select 
                v-model="searchForm.quality"
                @change="applyFilters"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
              >
                <option value="">Todas as qualidades</option>
                <option v-for="quality in qualityLines" :key="quality" :value="quality">
                  {{ formatQuality(quality) }}
                </option>
              </select>
            </div>

            <!-- Público Alvo -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Público</label>
              <select 
                v-model="searchForm.audience"
                @change="applyFilters"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
              >
                <option value="">Todos os públicos</option>
                <option v-for="audience in audiences" :key="audience" :value="audience">
                  {{ formatAudience(audience) }}
                </option>
              </select>
            </div>

            <!-- Faixa de Preço -->
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700 mb-2">Preço</label>
              <div class="space-y-3">
                <div>
                  <label class="text-xs text-gray-500">Mínimo</label>
                  <input 
                    v-model="searchForm.min_price"
                    @input="applyFilters"
                    type="number" 
                    min="0"
                    step="0.01"
                    placeholder="0,00"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
                  />
                </div>
                <div>
                  <label class="text-xs text-gray-500">Máximo</label>
                  <input 
                    v-model="searchForm.max_price"
                    @input="applyFilters"
                    type="number" 
                    min="0"
                    step="0.01"
                    placeholder="1000,00"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
                  />
                </div>
              </div>
            </div>

            <!-- Limpar Filtros -->
            <button 
              @click="clearFilters"
              class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition-colors"
            >
              Limpar Filtros
            </button>
          </div>
        </div>

        <!-- Área Principal de Produtos -->
        <div class="lg:col-span-3">
          <!-- Barra de Ordenação e Resultados -->
          <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <div class="mb-4 sm:mb-0">
                <p class="text-gray-600">
                  Mostrando {{ products.from || 0 }} - {{ products.to || 0 }} de {{ products.total || 0 }} produtos
                </p>
              </div>
              
              <div class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Ordenar por:</label>
                <select 
                  v-model="searchForm.sort"
                  @change="applyFilters"
                  class="px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500"
                >
                  <option value="featured">Destaques</option>
                  <option value="name">Nome A-Z</option>
                  <option value="price">Menor preço</option>
                  <option value="created_at">Mais recentes</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Grid de Produtos -->
          <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div 
              v-for="product in products.data" 
              :key="product.id"
              class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 group"
            >
              <!-- Imagem do Produto -->
              <div class="relative h-64 bg-gray-100 overflow-hidden">
                <img 
                  :src="product.primary_image_url || '/images/placeholder.jpg'"
                  :alt="product.name"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
                
                <!-- Badge de Qualidade -->
                <div class="absolute top-3 left-3">
                  <span class="bg-black text-white text-xs px-2 py-1 rounded-full">
                    {{ formatQuality(product.quality_line) }}
                  </span>
                </div>

                <!-- Badge de Destaque -->
                <div v-if="product.is_featured" class="absolute top-3 right-3">
                  <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    ⭐ Destaque
                  </span>
                </div>
              </div>

              <!-- Informações do Produto -->
              <div class="p-4">
                <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ product.name }}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ product.short_description }}</p>
                
                <!-- Preço -->
                <div class="mb-4">
                  <div v-if="product.sale_price" class="flex items-center space-x-2">
                    <span class="text-lg font-bold text-red-600">{{ formatPrice(product.sale_price) }}</span>
                    <span class="text-sm text-gray-500 line-through">{{ formatPrice(product.price) }}</span>
                  </div>
                  <div v-else>
                    <span class="text-lg font-bold text-gray-900">{{ formatPrice(product.price) }}</span>
                  </div>
                  <p class="text-xs text-gray-500 mt-1">ou 3x sem juros no cartão</p>
                </div>

                <!-- Botões de Ação -->
                <div class="space-y-2">
                  <Link 
                    :href="route('products.show', product.slug)"
                    class="w-full bg-red-600 text-white text-center py-2 px-4 rounded-md hover:bg-red-700 transition-colors block"
                  >
                    Ver Detalhes
                  </Link>
                  <button 
                    @click="addToCart(product)"
                    class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition-colors"
                  >
                    Adicionar ao Carrinho
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Paginação -->
          <div v-if="products.last_page > 1" class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-600">
                Página {{ products.current_page }} de {{ products.last_page }}
              </div>
              
              <div class="flex items-center space-x-2">
                <Link 
                  v-if="products.prev_page_url"
                  :href="products.prev_page_url"
                  class="px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  Anterior
                </Link>
                
                <span v-else class="px-3 py-2 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                  Anterior
                </span>

                <Link 
                  v-if="products.next_page_url"
                  :href="products.next_page_url"
                  class="px-3 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                >
                  Próxima
                </Link>
                
                <span v-else class="px-3 py-2 border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">
                  Próxima
                </span>
              </div>
            </div>
          </div>

          <!-- Mensagem quando não há produtos -->
          <div v-if="!products.data || products.data.length === 0" class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum produto encontrado</h3>
            <p class="text-gray-600 mb-6">Tente ajustar os filtros ou fazer uma nova busca.</p>
            <button 
              @click="clearFilters"
              class="bg-red-600 text-white py-2 px-6 rounded-md hover:bg-red-700 transition-colors"
            >
              Limpar Filtros
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
import { router, Link } from '@inertiajs/vue3'

// Props do componente
const props = defineProps({
  products: Object,
  categories: Array,
  qualityLines: Array,
  audiences: Array,
  priceRange: Object,
  filters: Object,
  currentPage: Number,
  totalPages: Number
})

// Form reativo para filtros
const searchForm = reactive({
  search: props.filters.search || '',
  category: props.filters.category || '',
  quality: props.filters.quality || '',
  audience: props.filters.audience || '',
  min_price: props.filters.min_price || '',
  max_price: props.filters.max_price || '',
  sort: props.filters.sort || 'featured',
  order: props.filters.order || 'asc'
})

// Debounce para aplicar filtros
let timeout = null

const applyFilters = () => {
  clearTimeout(timeout)
  timeout = setTimeout(() => {
    router.get(route('products.index'), searchForm, {
      preserveState: true,
      preserveScroll: true
    })
  }, 500)
}

const clearFilters = () => {
  Object.keys(searchForm).forEach(key => {
    if (key === 'sort') {
      searchForm[key] = 'featured'
    } else if (key === 'order') {
      searchForm[key] = 'asc'
    } else {
      searchForm[key] = ''
    }
  })
  
  router.get(route('products.index'), {}, {
    preserveState: true,
    preserveScroll: false
  })
}

// Formatação de dados
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

const formatAudience = (audience) => {
  const audiences = {
    'masculino': 'Masculino',
    'feminino': 'Feminino',
    'infantil': 'Infantil',
    'unissex': 'Unissex'
  }
  return audiences[audience] || audience
}

const formatPrice = (price) => {
  return 'R$ ' + parseFloat(price).toLocaleString('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })
}

// Adicionar ao carrinho (placeholder)
const addToCart = async (product) => {
  // Para produtos da listagem, vamos usar variações padrão
  const availableSizes = product.available_sizes ? product.available_sizes.split(',') : ['M']
  const availableColors = product.available_colors ? product.available_colors.split(',') : ['Branco']
  
  const defaultSize = availableSizes[0] || 'M'
  const defaultColor = availableColors[0] || 'Branco'
  
  const cartData = {
    product_id: product.id,
    size: defaultSize,
    color: defaultColor,
    quantity: 1
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
      alert(`✅ ${product.name} adicionado ao carrinho!\n\nTamanho: ${defaultSize} | Cor: ${defaultColor}\n\nPara escolher outras variações, visite a página do produto.`)
    } else {
      const errorData = await response.json()
      alert('Erro: ' + (errorData.message || 'Não foi possível adicionar o produto'))
    }
  } catch (error) {
    console.error('Erro ao adicionar ao carrinho:', error)
    alert('Erro ao adicionar produto. Tente novamente.')
  }
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
