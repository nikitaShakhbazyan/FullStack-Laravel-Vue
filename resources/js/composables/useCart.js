import { ref, computed } from 'vue';

const cartItems = ref([]);

const savedCart = localStorage.getItem('cart');
if (savedCart) {
    cartItems.value = JSON.parse(savedCart);
}

export function useCart() {
    const itemCount = computed(() => {
        return cartItems.value.reduce((sum, item) => sum + item.quantity, 0);
    });

    const totalAmount = computed(() => {
        return cartItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    });

    const addToCart = (product, quantity = 1) => {
        const existingItem = cartItems.value.find(item => item.product_id === product.id);

        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cartItems.value.push({
                product_id: product.id,
                name: product.name,
                price: product.price,
                quantity: quantity,
                image: product.image,
            });
        }

        saveCart();
    };

    const removeFromCart = (productId) => {
        cartItems.value = cartItems.value.filter(item => item.product_id !== productId);
        saveCart();
    };

    const updateQuantity = (productId, quantity) => {
        const item = cartItems.value.find(item => item.product_id === productId);
        if (item) {
            item.quantity = quantity;
            saveCart();
        }
    };

    const clearCart = () => {
        cartItems.value = [];
        saveCart();
    };

    const saveCart = () => {
        localStorage.setItem('cart', JSON.stringify(cartItems.value));
    };

    return {
        cartItems,
        itemCount,
        totalAmount,
        addToCart,
        removeFromCart,
        updateQuantity,
        clearCart,
    };
}
