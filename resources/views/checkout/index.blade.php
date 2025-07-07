@extends('layouts.app')

@section('title', 'Finalizar Compra - Vibe Garb')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Finalizar Compra</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li><a href="{{ route('cart.index') }}" class="text-purple-600 hover:text-purple-800">Carrinho</a></li>
                    <li><span class="text-gray-400 mx-2">></span></li>
                    <li><span class="text-gray-500">Checkout</span></li>
                </ol>
            </nav>
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Formulário de Dados -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Dados Pessoais -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Dados Pessoais</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                                <input type="text" 
                                       name="customer_name" 
                                       value="{{ old('customer_name', $userData['name'] ?? '') }}"
                                       required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                @error('customer_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                                <input type="email" 
                                       name="customer_email" 
                                       value="{{ old('customer_email', $userData['email'] ?? '') }}"
                                       required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                @error('customer_email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Telefone *</label>
                                <input type="tel" 
                                       name="customer_phone" 
                                       value="{{ old('customer_phone', $userData['phone'] ?? '') }}"
                                       placeholder="(11) 99999-9999"
                                       required 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                @error('customer_phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Endereço de Entrega -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Endereço de Entrega</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">CEP *</label>
                                <div class="flex gap-3">
                                    <input type="text" 
                                           id="zipcode"
                                           name="zipcode" 
                                           value="{{ old('zipcode', $userData['zipcode'] ?? '') }}"
                                           required 
                                           placeholder="00000-000" 
                                           maxlength="9"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    <button type="button" 
                                            id="search-zipcode-btn"
                                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-300">
                                        Buscar
                                    </button>
                                </div>
                                <div id="zipcode-loading" class="hidden text-sm text-blue-600 mt-1">Buscando CEP...</div>
                                <div id="zipcode-error" class="hidden text-sm text-red-600 mt-1"></div>
                                @error('zipcode')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div class="md:col-span-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Endereço *</label>
                                    <input type="text" 
                                           id="address"
                                           name="address" 
                                           value="{{ old('address', $userData['address'] ?? '') }}"
                                           required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('address')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Número *</label>
                                    <input type="text" 
                                           name="number" 
                                           value="{{ old('number') }}"
                                           required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Complemento</label>
                                <input type="text" 
                                       name="complement" 
                                       value="{{ old('complement') }}"
                                       placeholder="Apto, casa, bloco..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                @error('complement')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Bairro *</label>
                                    <input type="text" 
                                           id="neighborhood"
                                           name="neighborhood" 
                                           value="{{ old('neighborhood') }}"
                                           required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('neighborhood')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cidade *</label>
                                    <input type="text" 
                                           id="city"
                                           name="city" 
                                           value="{{ old('city', $userData['city'] ?? '') }}"
                                           required 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                                    <select id="state" 
                                            name="state" 
                                            required 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                                        <option value="">Selecione...</option>
                                        <option value="AC" {{ old('state', $userData['state'] ?? '') === 'AC' ? 'selected' : '' }}>Acre</option>
                                        <option value="AL" {{ old('state', $userData['state'] ?? '') === 'AL' ? 'selected' : '' }}>Alagoas</option>
                                        <option value="AP" {{ old('state', $userData['state'] ?? '') === 'AP' ? 'selected' : '' }}>Amapá</option>
                                        <option value="AM" {{ old('state', $userData['state'] ?? '') === 'AM' ? 'selected' : '' }}>Amazonas</option>
                                        <option value="BA" {{ old('state', $userData['state'] ?? '') === 'BA' ? 'selected' : '' }}>Bahia</option>
                                        <option value="CE" {{ old('state', $userData['state'] ?? '') === 'CE' ? 'selected' : '' }}>Ceará</option>
                                        <option value="DF" {{ old('state', $userData['state'] ?? '') === 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                        <option value="ES" {{ old('state', $userData['state'] ?? '') === 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                        <option value="GO" {{ old('state', $userData['state'] ?? '') === 'GO' ? 'selected' : '' }}>Goiás</option>
                                        <option value="MA" {{ old('state', $userData['state'] ?? '') === 'MA' ? 'selected' : '' }}>Maranhão</option>
                                        <option value="MT" {{ old('state', $userData['state'] ?? '') === 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                        <option value="MS" {{ old('state', $userData['state'] ?? '') === 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                        <option value="MG" {{ old('state', $userData['state'] ?? '') === 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                        <option value="PA" {{ old('state', $userData['state'] ?? '') === 'PA' ? 'selected' : '' }}>Pará</option>
                                        <option value="PB" {{ old('state', $userData['state'] ?? '') === 'PB' ? 'selected' : '' }}>Paraíba</option>
                                        <option value="PR" {{ old('state', $userData['state'] ?? '') === 'PR' ? 'selected' : '' }}>Paraná</option>
                                        <option value="PE" {{ old('state', $userData['state'] ?? '') === 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                        <option value="PI" {{ old('state', $userData['state'] ?? '') === 'PI' ? 'selected' : '' }}>Piauí</option>
                                        <option value="RJ" {{ old('state', $userData['state'] ?? '') === 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                        <option value="RN" {{ old('state', $userData['state'] ?? '') === 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                        <option value="RS" {{ old('state', $userData['state'] ?? '') === 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                        <option value="RO" {{ old('state', $userData['state'] ?? '') === 'RO' ? 'selected' : '' }}>Rondônia</option>
                                        <option value="RR" {{ old('state', $userData['state'] ?? '') === 'RR' ? 'selected' : '' }}>Roraima</option>
                                        <option value="SC" {{ old('state', $userData['state'] ?? '') === 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                        <option value="SP" {{ old('state', $userData['state'] ?? '') === 'SP' ? 'selected' : '' }}>São Paulo</option>
                                        <option value="SE" {{ old('state', $userData['state'] ?? '') === 'SE' ? 'selected' : '' }}>Sergipe</option>
                                        <option value="TO" {{ old('state', $userData['state'] ?? '') === 'TO' ? 'selected' : '' }}>Tocantins</option>
                                    </select>
                                    @error('state')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Forma de Pagamento -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Forma de Pagamento</h2>
                        
                        <div class="space-y-4">
                            <!-- PIX -->
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-400 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50">
                                <input type="radio" name="payment_method" value="pix" class="mr-3" required>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span class="text-lg font-medium text-gray-900 mr-2">PIX</span>
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded">5% OFF</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Aprovação instantânea</p>
                                    <p class="text-lg font-bold text-green-600">{{ $cartSummary['formatted_pix_total'] }}</p>
                                </div>
                            </label>

                            <!-- Boleto -->
                            <label class="flex items-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-400 has-[:checked]:border-purple-600 has-[:checked]:bg-purple-50">
                                <input type="radio" name="payment_method" value="boleto" class="mr-3">
                                <div class="flex-1">
                                    <div class="text-lg font-medium text-gray-900">Boleto Bancário</div>
                                    <p class="text-sm text-gray-600">Vencimento em 3 dias úteis</p>
                                    <p class="text-lg font-bold text-gray-700">{{ $cartSummary['formatted_total'] }}</p>
                                </div>
                            </label>

                            <!-- Cartão (futuro) -->
                            <label class="flex items-center p-4 border-2 border-gray-200 rounded-lg cursor-not-allowed opacity-60">
                                <input type="radio" name="payment_method" value="card" class="mr-3" disabled>
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <span class="text-lg font-medium text-gray-500 mr-2">Cartão de Crédito</span>
                                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">Em breve</span>
                                    </div>
                                    <p class="text-sm text-gray-500">Parcelamento em até 12x</p>
                                </div>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
                
                <!-- Resumo do Pedido -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-28">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                        
                        <div class="space-y-3">
                            @foreach($cartItems as $item)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="{{ $item->image ?? '/images/default-product.png' }}" alt="{{ $item->name }}" class="w-12 h-12 object-cover rounded-md mr-3">
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $item->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $item->color }} - {{ $item->size }}</p>
                                            <p class="text-sm text-gray-500">Qtd: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-700 font-semibold">R$ {{ number_format($item->quantity * $item->unit_price, 2, ',', '.') }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-semibold text-gray-800">{{ $cartSummary['formatted_subtotal'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Frete</span>
                                <span class="font-semibold text-gray-800">{{ $cartSummary['formatted_shipping'] }}</span>
                            </div>
                            <div class="flex justify-between text-green-600" id="pix-discount-summary" style="display: none;">
                                <span class="text-gray-600">Desconto (PIX)</span>
                                <span class="font-semibold text-green-600">- R$ {{ number_format($cartSummary['pix_discount'], 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-purple-700" id="total-price">{{ $cartSummary['formatted_total'] }}</span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <button type="submit" 
                                id="submit-checkout-btn"
                                class="w-full bg-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-purple-700 transition duration-300 flex items-center justify-center">
                            <span id="submit-text">Finalizar Compra</span>
                            <svg id="submit-loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchZipcodeBtn = document.getElementById('search-zipcode-btn');
    const zipcodeField = document.getElementById('zipcode');
    const addressField = document.getElementById('address');
    const neighborhoodField = document.getElementById('neighborhood');
    const cityField = document.getElementById('city');
    const stateField = document.getElementById('state');
    const loadingDiv = document.getElementById('zipcode-loading');
    const errorDiv = document.getElementById('zipcode-error');

    const clearAddressFields = () => {
        addressField.value = '';
        neighborhoodField.value = '';
        cityField.value = '';
        stateField.value = '';
    };

    const searchZipcode = async () => {
        const zipcode = zipcodeField.value.replace(/\D/g, ''); 
        if (zipcode.length !== 8) {
            errorDiv.textContent = 'CEP inválido. Por favor, digite 8 números.';
            errorDiv.classList.remove('hidden');
            clearAddressFields();
            return;
        }

        loadingDiv.classList.remove('hidden');
        errorDiv.classList.add('hidden');
        
        try {
            const response = await fetch(`https://viacep.com.br/ws/${zipcode}/json/`);
            const data = await response.json();

            if (data.erro) {
                errorDiv.textContent = 'CEP não encontrado. Verifique e tente novamente.';
                errorDiv.classList.remove('hidden');
                clearAddressFields();
            } else {
                addressField.value = data.logradouro;
                neighborhoodField.value = data.bairro;
                cityField.value = data.localidade;
                stateField.value = data.uf;
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
            errorDiv.textContent = 'Erro ao buscar CEP. Tente novamente mais tarde.';
            errorDiv.classList.remove('hidden');
            clearAddressFields();
        } finally {
            loadingDiv.classList.add('hidden');
        }
    };

    if (searchZipcodeBtn) {
        searchZipcodeBtn.addEventListener('click', searchZipcode);
    }

    if (zipcodeField) {
        zipcodeField.addEventListener('input', () => {
            let value = zipcodeField.value.replace(/\D/g, '');
            if (value.length > 5) {
                value = value.slice(0, 5) + '-' + value.slice(5, 8);
            }
            zipcodeField.value = value;
            
            if (value.replace(/\D/g, '').length === 8) {
                searchZipcode();
            }
        });
    }

    const checkoutForm = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('submit-checkout-btn');
    const submitText = document.getElementById('submit-text');
    const submitLoading = document.getElementById('submit-loading');

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', () => {
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
        });
    }

    // Lógica para mostrar/esconder desconto do PIX
    const paymentMethodRadios = document.querySelectorAll('input[name="payment_method"]');
    const pixDiscountSummary = document.getElementById('pix-discount-summary');
    const totalPriceEl = document.getElementById('total-price');
    const originalTotal = '{{ $cartSummary['formatted_total'] }}';
    const pixTotal = '{{ $cartSummary['formatted_pix_total'] }}';

    paymentMethodRadios.forEach(radio => {
        radio.addEventListener('change', (event) => {
            if (event.target.value === 'pix') {
                pixDiscountSummary.style.display = 'flex';
                totalPriceEl.textContent = pixTotal;
            } else {
                pixDiscountSummary.style.display = 'none';
                totalPriceEl.textContent = originalTotal;
            }
        });
    });

    // Estado inicial
    if (document.querySelector('input[name="payment_method"]:checked')?.value === 'pix') {
        pixDiscountSummary.style.display = 'flex';
        totalPriceEl.textContent = pixTotal;
    }

});
</script>
@endpush 