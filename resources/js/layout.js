// Layout functionality for Vibe Garb
document.addEventListener('DOMContentLoaded', function() {
    console.log('Layout JS loaded');
    
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            const isHidden = mobileMenu.style.display === 'none' || !mobileMenu.style.display;
            mobileMenu.style.display = isHidden ? 'block' : 'none';
        });
    }

    // Update cart count function
    function updateCartCount() {
        // Simulate cart count for now
        const cartCount = document.getElementById('cart-count');
        if (cartCount) {
            cartCount.textContent = '0';
        }
    }

    // Update cart count on page load
    updateCartCount();

    // Search functionality
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    // Simple search redirection
                    window.location.href = '/produtos?search=' + encodeURIComponent(query);
                }
            }
        });
    }
}); 