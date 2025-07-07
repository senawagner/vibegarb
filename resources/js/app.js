import './bootstrap';

// =====================================================
// SISTEMA DE NOTIFICAÇÕES
// =====================================================
const NotificationSystem = {
    show(message, type = 'success') {
        const notification = document.getElementById('notification');
        const messageElement = document.getElementById('notification-message');

        if (!notification || !messageElement) {
            console.warn('Sistema de notificações não encontrado no DOM');
            return;
        }

        // Remove classes anteriores
        notification.classList.remove('hidden', 'bg-green-500', 'bg-red-500', 'bg-blue-500', 'transform', '-translate-y-full', 'opacity-0');
        
        // Define a cor baseada no tipo
        const colorClass = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        notification.classList.add(colorClass);
        
        messageElement.textContent = message;
        
        // Animação de entrada
        setTimeout(() => {
            notification.classList.remove('-translate-y-full', 'opacity-0');
            notification.classList.add('translate-y-0', 'opacity-100');
        }, 10);

        // Remove automaticamente após 5 segundos
        setTimeout(() => {
            this.hide();
        }, 5000);
    },

    hide() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.classList.add('-translate-y-full', 'opacity-0');
            setTimeout(() => {
                notification.classList.add('hidden');
            }, 300);
        }
    }
};

// =====================================================
// SISTEMA DE CARRINHO
// =====================================================
const CartSystem = {
    // Atualiza o contador do carrinho no header
    updateCartCount() {
        fetch('/api/cart/count')
            .then(response => response.json())
            .then(data => {
                const cartCountElement = document.getElementById('cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = data.count || 0;
                    // Mostra/esconde o badge baseado na quantidade
                    if (data.count > 0) {
                        cartCountElement.classList.remove('hidden');
                    } else {
                        cartCountElement.classList.add('hidden');
                    }
                }
            })
            .catch(error => {
                console.error('Erro ao buscar contagem do carrinho:', error);
            });
    },

    // Adiciona item ao carrinho via AJAX
    addItem(formData) {
        return fetch('/carrinho/adicionar', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                NotificationSystem.show('Item adicionado ao carrinho com sucesso!', 'success');
                this.updateCartCount();
                return data;
            } else {
                throw new Error(data.message || 'Erro ao adicionar item');
            }
        })
        .catch(error => {
            console.error('Erro ao adicionar item ao carrinho:', error);
            NotificationSystem.show('Erro ao adicionar item ao carrinho', 'error');
            throw error;
        });
    },

    // Atualiza quantidade de um item
    updateQuantity(itemKey, newQuantity) {
        if (newQuantity < 1 || newQuantity > 10) return;
        
        fetch(`/carrinho/atualizar/${itemKey}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ quantity: newQuantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Recarrega para atualizar os cálculos
            } else {
                NotificationSystem.show('Erro ao atualizar quantidade', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar quantidade:', error);
            NotificationSystem.show('Erro ao atualizar quantidade', 'error');
        });
    },

    // Remove item do carrinho
    removeItem(itemKey) {
        if (!confirm('Tem certeza que deseja remover este item?')) return;
        
        fetch(`/carrinho/remover/${itemKey}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                NotificationSystem.show('Item removido do carrinho', 'success');
                location.reload();
            } else {
                NotificationSystem.show('Erro ao remover item', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao remover item:', error);
            NotificationSystem.show('Erro ao remover item', 'error');
        });
    },

    // Limpa todo o carrinho
    clear() {
        if (!confirm('Tem certeza que deseja limpar todo o carrinho?')) return;
        
        fetch('/carrinho/limpar', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                NotificationSystem.show('Carrinho limpo com sucesso', 'success');
                location.reload();
            } else {
                NotificationSystem.show('Erro ao limpar carrinho', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao limpar carrinho:', error);
            NotificationSystem.show('Erro ao limpar carrinho', 'error');
        });
    }
};

// =====================================================
// SISTEMA DE PRODUTOS
// =====================================================
const ProductSystem = {
    // Controla a quantidade no formulário de produto
    updateQuantity(action) {
        const input = document.getElementById('quantity');
        if (!input) return;

        const current = parseInt(input.value) || 1;
        let newValue = current;

        if (action === 'increase' && current < 10) {
            newValue = current + 1;
        } else if (action === 'decrease' && current > 1) {
            newValue = current - 1;
        }

        input.value = newValue;
    },

    // Valida o formulário de adicionar ao carrinho
    validateAddToCartForm(form) {
        const color = form.querySelector('input[name="color"]:checked');
        const size = form.querySelector('input[name="size"]:checked');
        
        if (!color || !size) {
            NotificationSystem.show('Por favor, selecione cor e tamanho!', 'error');
            return false;
        }
        
        return true;
    },

    // Intercepta o envio do formulário de adicionar ao carrinho
    handleAddToCartForm(form) {
        if (!this.validateAddToCartForm(form)) {
            return;
        }

        const formData = new FormData(form);
        CartSystem.addItem(formData);
    }
};

// =====================================================
// SISTEMA DE FORMULÁRIOS
// =====================================================
const FormSystem = {
    // Formata CEP automaticamente
    formatCEP(input) {
        let value = input.value.replace(/\D/g, '');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        input.value = value;
    },

    // Calcula frete
    calculateShipping(form) {
        const zipcode = form.querySelector('#zipcode').value;
        const btn = form.querySelector('#calculate-shipping-btn');
        const errorDiv = form.querySelector('#shipping-error');
        
        if (errorDiv) errorDiv.textContent = '';
        
        if (btn) {
            btn.textContent = 'Calculando...';
            btn.disabled = true;
        }

        const csrfToken = form.querySelector('input[name="_token"]')?.value || 
                         document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/checkout/calcular-frete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ zipcode: zipcode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                NotificationSystem.show('Frete calculado com sucesso!', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                if (errorDiv) {
                    errorDiv.textContent = data.error || 'Não foi possível calcular o frete.';
                }
                NotificationSystem.show(data.error || 'Erro ao calcular frete', 'error');
            }
        })
        .catch(error => {
            console.error('Erro ao calcular frete:', error);
            if (errorDiv) {
                errorDiv.textContent = 'Ocorreu um erro de comunicação. Tente novamente.';
            }
            NotificationSystem.show('Erro ao calcular frete', 'error');
        })
        .finally(() => {
            if (btn) {
                btn.textContent = 'Calcular';
                btn.disabled = false;
            }
        });
    }
};

// =====================================================
// INICIALIZAÇÃO E EVENT LISTENERS
// =====================================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 ViteGarb App.js carregado com sucesso!');

    // ===== CARRINHO =====
    
    // Atualiza contador do carrinho na inicialização
    CartSystem.updateCartCount();

    // Formulário de adicionar ao carrinho - SELETOR CORRIGIDO
    const addToCartForm = document.getElementById('add-to-cart-form');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            ProductSystem.handleAddToCartForm(this);
        });
    }

    // Formulários de adicionar ao carrinho (fallback para outros formulários)
    const addToCartForms = document.querySelectorAll('form[action*="carrinho/adicionar"]');
    addToCartForms.forEach(form => {
        // Só adiciona se não for o formulário principal já processado
        if (form.id !== 'add-to-cart-form') {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                ProductSystem.handleAddToCartForm(this);
            });
        }
    });

    // Botões de quantidade nos produtos - CORRIGIDO
    document.addEventListener('click', function(e) {
        // Para botões com data-quantity-action (view show-simple.blade.php)
        if (e.target.matches('[data-quantity-action]')) {
            const action = e.target.getAttribute('data-quantity-action');
            ProductSystem.updateQuantity(action);
        }
        
        // Para botões na view show.blade.php (sem data attributes)
        if (e.target.closest('button[type="button"]')) {
            const button = e.target.closest('button[type="button"]');
            const quantityInput = document.getElementById('quantity');
            
            if (quantityInput && button.parentElement.contains(quantityInput)) {
                const svg = button.querySelector('svg path');
                if (svg) {
                    const pathData = svg.getAttribute('d');
                    // Detecta se é botão de diminuir (linha horizontal) ou aumentar (cruz)
                    if (pathData.includes('M20 12H4')) {
                        ProductSystem.updateQuantity('decrease');
                    } else if (pathData.includes('M12 6v6m0 0v6m0-6h6m-6 0H6')) {
                        ProductSystem.updateQuantity('increase');
                    }
                }
            }
        }
    });

    // Ações do carrinho na página do carrinho
    document.addEventListener('click', function(e) {
        const target = e.target.closest('[data-cart-action]');
        if (!target) return;

        const action = target.getAttribute('data-cart-action');
        const itemKey = target.getAttribute('data-item-key');
        const currentQuantity = parseInt(target.getAttribute('data-current-quantity')) || 0;

        switch (action) {
            case 'increase':
                CartSystem.updateQuantity(itemKey, currentQuantity + 1);
                break;
            case 'decrease':
                CartSystem.updateQuantity(itemKey, currentQuantity - 1);
                break;
            case 'remove':
                CartSystem.removeItem(itemKey);
                break;
            case 'clear':
                CartSystem.clear();
                break;
        }
    });

    // ===== FORMULÁRIOS =====
    
    // Formatação de CEP
    const cepInputs = document.querySelectorAll('input[placeholder*="00000-000"], input[name="zipcode"]');
    cepInputs.forEach(input => {
        input.addEventListener('input', function() {
            FormSystem.formatCEP(this);
        });
    });

    // Formulário de cálculo de frete
    const shippingForm = document.getElementById('shipping-form');
    if (shippingForm) {
        shippingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            FormSystem.calculateShipping(this);
        });
    }

    // ===== NOTIFICAÇÕES =====
    
    // Fecha notificação ao clicar
    const notification = document.getElementById('notification');
    if (notification) {
        notification.addEventListener('click', function() {
            NotificationSystem.hide();
        });
    }

    // ===== ADMIN =====
    
    // Confirmações de exclusão
    document.addEventListener('click', function(e) {
        if (e.target.matches('[onclick*="confirm"]')) {
            e.preventDefault();
            const confirmText = e.target.getAttribute('onclick').match(/confirm\('([^']+)'/);
            if (confirmText && confirm(confirmText[1])) {
                // Se for um link, navega para o href
                if (e.target.tagName === 'A') {
                    window.location.href = e.target.href;
                }
                // Se for um formulário, submete
                else if (e.target.form) {
                    e.target.form.submit();
                }
            }
        }
    });

    console.log('✅ Todos os event listeners foram configurados!');
});

// Expor sistemas globalmente para debug (apenas em desenvolvimento)
if (window.location.hostname === 'localhost' || window.location.hostname.includes('127.0.0.1')) {
    window.ViteGarb = {
        Cart: CartSystem,
        Product: ProductSystem,
        Form: FormSystem,
        Notification: NotificationSystem
    };
    console.log('🔧 Sistemas expostos globalmente para debug: window.ViteGarb');
}
