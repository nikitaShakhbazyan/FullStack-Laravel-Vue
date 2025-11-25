<template>
  <div>
    <AppNavbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Shopping Cart</h1>

      <div v-if="cartItems.length === 0" class="text-center py-12">
        <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <p class="mt-4 text-gray-500">Your cart is empty</p>
        <router-link to="/products">
          <AppButton class="mt-4">Continue Shopping</AppButton>
        </router-link>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
          <AppCard v-for="item in cartItems" :key="item.product_id" class="mb-4">
            <div class="flex items-center gap-4">
              <img
                :src="item.image || '/placeholder.jpg'"
                :alt="item.name"
                class="w-24 h-24 object-cover rounded"
              />

              <div class="flex-1">
                <h3 class="text-lg font-medium text-gray-900">{{ item.name }}</h3>
                <p class="text-gray-600">${{ parseFloat(item.price).toFixed(2) }}</p>
              </div>

              <div class="flex items-center gap-2">
                <button
                  @click="updateQuantity(item.product_id, item.quantity - 1)"
                  class="p-1 rounded hover:bg-gray-100"
                  :disabled="item.quantity <= 1"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                  </svg>
                </button>

                <span class="w-12 text-center">{{ item.quantity }}</span>

                <button
                  @click="updateQuantity(item.product_id, item.quantity + 1)"
                  class="p-1 rounded hover:bg-gray-100"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                  </svg>
                </button>
              </div>

              <div class="text-right">
                <p class="text-lg font-medium text-gray-900">
                  ${{ (item.price * item.quantity).toFixed(2) }}
                </p>
              </div>

              <button
                @click="removeFromCart(item.product_id)"
                class="p-2 text-red-600 hover:bg-red-50 rounded"
              >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </AppCard>
        </div>

        <div class="lg:col-span-1">
          <AppCard>
            <template #header>
              <h2 class="text-xl font-bold text-gray-900">Order Summary</h2>
            </template>

            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span class="font-medium">${{ totalAmount.toFixed(2) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Shipping</span>
                <span class="font-medium">Free</span>
              </div>
              <div class="border-t pt-2 flex justify-between">
                <span class="text-lg font-bold">Total</span>
                <span class="text-lg font-bold">${{ totalAmount.toFixed(2) }}</span>
              </div>
            </div>

            <template #footer>
              <AppButton
                class="w-full"
                :loading="checkoutLoading"
                @click="handleCheckout"
              >
                Proceed to Checkout
              </AppButton>
            </template>
          </AppCard>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useCart } from '@/composables/useCart';
import { useAuth } from '@/composables/useAuth';
import { orderService } from '@/services/api';
import AppNavbar from '@/components/AppNavbar.vue';
import AppCard from '@/components/AppCard.vue';
import AppButton from '@/components/AppButton.vue';

const router = useRouter();
const { cartItems, totalAmount, updateQuantity, removeFromCart, clearCart } = useCart();
const { isAuthenticated } = useAuth();

const checkoutLoading = ref(false);

const handleCheckout = async () => {
  if (!isAuthenticated.value) {
    router.push('/login');
    return;
  }

  checkoutLoading.value = true;

  try {
    const items = cartItems.value.map(item => ({
      product_id: item.product_id,
      quantity: item.quantity,
    }));

    await orderService.create({ items });
    clearCart();
    router.push('/orders');
  } catch (error) {
    console.error('Checkout failed:', error);
    alert(error.response?.data?.message || 'Failed to create order');
  } finally {
    checkoutLoading.value = false;
  }
};
</script>
