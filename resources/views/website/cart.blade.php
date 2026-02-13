@extends('website.layouts.master')

@section('title', 'Shopping Cart - ' . ($siteSettings['site_name'] ?? 'Fashion Store'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Shopping Cart</h1>
    
    <!-- Stock Error Message (Hidden by default) -->
    <div id="stockErrorMessage" class="hidden mb-4 p-4 bg-red-50 border border-red-300 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-semibold text-red-800">Stock Limit Reached</p>
                <p id="stockErrorText" class="text-sm text-red-700 mt-1"></p>
            </div>
            <button onclick="hideStockError()" class="text-red-600 hover:text-red-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    
    <div id="cartPageContent">
        <div class="text-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-gray-900 mx-auto"></div>
            <p class="mt-4 text-gray-600">Loading cart...</p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadCartPage();
});

function loadCartPage() {
    fetch('{{ route('cart.data') }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.cart_items.length === 0) {
                    // Redirect to home if cart is empty
                    window.location.href = '{{ route('home') }}';
                    return;
                }
                
                renderCartPage(data);
            }
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            document.getElementById('cartPageContent').innerHTML = `
                <div class="text-center py-12">
                    <p class="text-red-600">Error loading cart. Please try again.</p>
                </div>
            `;
        });
}

function renderCartPage(data) {
    const cartItems = data.cart_items;
    const totals = data.totals;
    
    let html = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
    `;
    
    cartItems.forEach(item => {
        const variantInfo = [];
        if (item.color_name) variantInfo.push(`Color: ${item.color_name}`);
        if (item.size_abbr) variantInfo.push(`Size: ${item.size_abbr}`);
        const variantText = variantInfo.join(' | ');
        
        html += `
            <div class="flex items-center gap-4 p-4 border-b" data-key="${item.key}">
                <img src="${item.image}" alt="${item.name}" class="w-24 h-24 object-cover rounded">
                <div class="flex-1">
                    <h3 class="font-semibold">${item.name}</h3>
                    ${variantText ? `<p class="text-sm text-gray-600">${variantText}</p>` : ''}
                    <p class="text-lg font-bold mt-2">₹${item.price}</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="updateQty('${item.key}', ${item.quantity - 1})" class="px-3 py-1 border rounded hover:bg-gray-100" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                    <span class="px-4">${item.quantity}</span>
                    <button onclick="updateQty('${item.key}', ${item.quantity + 1})" class="px-3 py-1 border rounded hover:bg-gray-100">+</button>
                </div>
                <button onclick="removeItem('${item.key}')" class="text-red-600 hover:text-red-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        `;
    });
    
    html += `
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow p-6 sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span>₹${totals.subtotal_formatted}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Shipping</span>
                            <span>Calculated at checkout</span>
                        </div>
                    </div>
                    <div class="border-t pt-4 mb-6">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total</span>
                            <span>₹${totals.subtotal_formatted}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout') }}" class="block w-full bg-black text-white text-center py-3 rounded hover:bg-gray-800 transition">
                        Proceed to Checkout
                    </a>
                    <a href="{{ route('shop') }}" class="block w-full text-center py-3 mt-2 border rounded hover:bg-gray-50 transition">
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('cartPageContent').innerHTML = html;
}

function updateQty(key, newQty) {
    if (newQty < 1) return;
    
    fetch('{{ route('cart.update') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key: key, quantity: newQty })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            renderCartPage(data);
            if (window.updateCartCount) window.updateCartCount();
            hideStockError(); // Hide error on success
        } else {
            // Show inline error message
            showStockError(data.message || 'Failed to update quantity');
        }
    })
    .catch(error => {
        console.error('Error updating quantity:', error);
        showStockError('Failed to update quantity. Please try again.');
    });
}

function showStockError(message) {
    const errorDiv = document.getElementById('stockErrorMessage');
    const errorText = document.getElementById('stockErrorText');
    
    if (errorDiv && errorText) {
        errorText.textContent = message;
        errorDiv.classList.remove('hidden');
        
        // Scroll to top to show message
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            errorDiv.classList.add('hidden');
        }, 5000);
    }
}

function hideStockError() {
    const errorDiv = document.getElementById('stockErrorMessage');
    if (errorDiv) {
        errorDiv.classList.add('hidden');
    }
}

function removeItem(key) {
    if (!confirm('Remove this item from cart?')) return;
    
    fetch('{{ route('cart.remove') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ key: key })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.cart_items.length === 0) {
                window.location.href = '{{ route('home') }}';
            } else {
                renderCartPage(data);
                if (window.updateCartCount) window.updateCartCount();
            }
        }
    });
}
</script>
@endsection
