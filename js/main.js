document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.add-to-cart, .add-to-cart-btn');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            let quantity = 1;
            
            // If on product page, get the quantity from input
            if(this.classList.contains('add-to-cart-btn')) {
                quantity = document.getElementById('quantity').value;
            }
            
            addToCart(productId, quantity);
        });
    });
    
    // Update cart count in header
    updateCartCount();
    
    // Quantity change in cart
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const productId = this.getAttribute('data-id');
            const quantity = this.value;
            
            updateCartItem(productId, quantity);
        });
    });
    
    // Remove item from cart
    const removeButtons = document.querySelectorAll('.remove-from-cart');
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            
            removeFromCart(productId);
        });
    });
});

function addToCart(productId, quantity) {
    fetch('/honkaishop/includes/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=add&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            alert('Product added to cart!');
            updateCartCount();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function updateCartItem(productId, quantity) {
    fetch('/honkaishop/includes/cart_actions.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function removeFromCart(productId) {
    if(confirm('Are you sure you want to remove this item from your cart?')) {
        fetch('/honkaishop/includes/cart_actions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=remove&product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

function updateCartCount() {
    fetch('/honkaishop/includes/cart_actions.php?action=count')
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            document.querySelectorAll('.cart-count').forEach(el => {
                el.textContent = data.count;
            });
        }
    })
    .catch(error => console.error('Error:', error));
}