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
                            
                            <!-- Cálculo de Frete -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-blue-900">Calcular Frete</span>
                                    <button type="button" 
                                            id="calculate-shipping-btn"
                                            class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition duration-300">
                                        Calcular
                                    </button>
                                </div>
                                <div id="shipping-result" class="mt-2 hidden">
                                    <div class="text-sm text-blue-800">
                                        <span id="shipping-cost-display"></span> - 
                                        <span id="shipping-days-display"></span>
                                    </div>
                                </div>
                                <div id="shipping-loading" class="hidden text-sm text-blue-600 mt-2">Calculando frete...</div>
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
                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Resumo do Pedido -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Resumo do Pedido</h2>
                        
                        <!-- Itens -->
                        <div class="space-y-3 mb-4">
                            @foreach($cartItems as $item)
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center text-2xl">👕</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-600">{{ $item['color'] }} • {{ $item['size'] }} • Qtd: {{ $item['quantity'] }}</p>
                                </div>
                                <div class="text-sm font-medium text-gray-900">{{ $item['formatted_total'] }}</div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Cálculos -->
                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal</span>
                                <span>{{ $cartSummary['formatted_subtotal'] }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Frete</span>
                                <span id="shipping-display">{{ $cartSummary['formatted_shipping'] }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span>Total</span>
                                <span id="total-display">{{ $cartSummary['formatted_total'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botão Finalizar -->
                    <button type="submit" 
                            id="submit-btn"
                            class="w-full bg-purple-600 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-purple-700 transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        Confirmar Pedido
                    </button>

                    <!-- Segurança -->
                    <div class="bg-gray-50 p-4 rounded-lg text-center">
                        <div class="flex items-center justify-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Compra Segura
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                SSL Criptografado
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Função para formatar CEP
    function formatZipcode(input) {
        let value = input.value.replace(/\D/g, '');
        if (value.length > 5) {
            value = value.replace(/^(\d{5})(\d{1,3})/, '$1-$2');
        }
        input.value = value;
    }

    // Aplicar formatação no CEP
    const zipcodeInput = document.getElementById('zipcode');
    zipcodeInput.addEventListener('input', function() {
        formatZipcode(this);
    });

    // Buscar CEP
    document.getElementById('search-zipcode-btn').addEventListener('click', function() {
        const zipcode = zipcodeInput.value.replace(/\D/g, '');
        
        if (zipcode.length !== 8) {
            showZipcodeError('CEP deve ter 8 dígitos');
            return;
        }

        showZipcodeLoading(true);
        hideZipcodeError();

        fetch('{{ route("checkout.search_zipcode") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || document.querySelector('[name="_token"]').value
            },
            body: JSON.stringify({ zipcode: zipcode })
        })
        .then(response => response.json())
        .then(data => {
            showZipcodeLoading(false);
            
            if (data.error) {
                showZipcodeError(data.error);
                return;
            }

            // Preencher campos
            document.getElementById('address').value = data.address || '';
            document.getElementById('neighborhood').value = data.neighborhood || '';
            document.getElementById('city').value = data.city || '';
            document.getElementById('state').value = data.state || '';
            
            // Auto-calcular frete
            calculateShipping();
        })
        .catch(error => {
            showZipcodeLoading(false);
            showZipcodeError('Erro ao buscar CEP');
        });
    });

    // Calcular frete
    document.getElementById('calculate-shipping-btn').addEventListener('click', calculateShipping);

    function calculateShipping() {
        const zipcode = zipcodeInput.value.replace(/\D/g, '');
        
        if (zipcode.length !== 8) {
            alert('Informe um CEP válido para calcular o frete');
            return;
        }

        document.getElementById('shipping-loading').classList.remove('hidden');
        document.getElementById('shipping-result').classList.add('hidden');

        fetch('{{ route("checkout.calculate_shipping") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') || document.querySelector('[name="_token"]').value
            },
            body: JSON.stringify({ zipcode: zipcode })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('shipping-loading').classList.add('hidden');
            document.getElementById('shipping-result').classList.remove('hidden');
            
            document.getElementById('shipping-cost-display').textContent = data.formatted_cost;
            document.getElementById('shipping-days-display').textContent = data.shipping_days;
            document.getElementById('shipping-display').textContent = data.formatted_cost;
            
            // Atualizar total baseado na forma de pagamento selecionada
            updateTotalDisplay();
        })
        .catch(error => {
            document.getElementById('shipping-loading').classList.add('hidden');
            alert('Erro ao calcular frete');
        });
    }

    // Atualizar preço total baseado na forma de pagamento selecionada
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', updateTotalDisplay);
    });

    function updateTotalDisplay() {
        const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
        const totalDisplay = document.getElementById('total-display');
        
        if (selectedPayment && selectedPayment.value === 'pix') {
            totalDisplay.textContent = '{{ $cartSummary["formatted_pix_total"] }}';
        } else {
            totalDisplay.textContent = '{{ $cartSummary["formatted_total"] }}';
        }
    }

    function showZipcodeLoading(show) {
        const loading = document.getElementById('zipcode-loading');
        if (show) {
            loading.classList.remove('hidden');
        } else {
            loading.classList.add('hidden');
        }
    }

    function showZipcodeError(message) {
        const error = document.getElementById('zipcode-error');
        error.textContent = message;
        error.classList.remove('hidden');
    }

    function hideZipcodeError() {
        document.getElementById('zipcode-error').classList.add('hidden');
    }

    // Validação antes de enviar
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            e.preventDefault();
            alert('Selecione uma forma de pagamento');
            return;
        }

        // Desabilitar botão para evitar duplo clique
        document.getElementById('submit-btn').disabled = true;
        document.getElementById('submit-btn').textContent = 'Processando...';
    });
});
</script>
@endpush
@endsection 