<template>
  <div>
    <AppNavbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <AppLoading v-if="loading" message="Loading product..." />

      <div v-else-if="error" class="text-center py-12">
        <p class="text-red-600">{{ error }}</p>
        <router-link to="/products">
          <AppButton class="mt-4">Back to Products</AppButton>
        </router-link>
      </div>

      <div v-else-if="product" class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div>
          <img
            :src="product.image || '/placeholder.jpg'"
            :alt="product.name"
            class="w-full h-96 object-cover rounded-lg"
          />
        </div>

        <div>
          <nav class="text-sm mb-4">
            <router-link to="/products" class="text-blue-600 hover:text-blue-800">
              Products
            </router-link>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-500">{{ product.category?.name }}</span>
          </nav>

          <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>

          <div class="mb-6">
            <span class="text-3xl font-bold text-gray-900">
              ${{ parseFloat(product.price).toFixed(2) }}
            </span>
          </div>

          <div class="mb-6">
            <p class="text-gray-700">{{ product.description }}</p>
          </div>

          <div class="mb-6">
            <p class="text-sm text-gray-600">
              <span class="font-medium">Category:</span> {{ product.category?.name }}
            </p>
            <p class="text-sm text-gray-600">
              <span class="font-medium">Stock:</span>
              <span :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                {{ product.stock > 0 ? `${product.stock} available` : 'Out of stock' }}
              </span>
            </p>
          </div>

          <div class="flex items-center gap-4 mb-6">
            <label class="text-sm font-medium text-gray-700">Quantity:</label>
            <input
              v-model.number="quantity"
              type="number"
              min="1"
              :max="product.stock"
              class="w-20 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>

          <AppButton
            size="lg"
            class="w-full md:w-auto"
            :disabled="product.stock === 0"
            @click="handleAddToCart"
          >
            {{ product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
          </AppButton>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { productService } from '@/services/api';
import { useCart } from '@/composables/useCart';
import AppNavbar from '@/components/AppNavbar.vue';
import AppButton from '@/components/AppButton.vue';
import AppLoading from '@/components/AppLoading.vue';

const route = useRoute();
const router = useRouter();
const { addToCart } = useCart();

const loading = ref(true);
const error = ref('');
const product = ref(null);
const quantity = ref(1);

const loadProduct = async () => {
  try {
    const response = await productService.getOne(route.params.id);
    product.value = response.data;
  } catch (err) {
    error.value = 'Failed to load product';
    console.error('Error loading product:', err);
  } finally {
    loading.value = false;
  }
};

const handleAddToCart = () => {
  addToCart(product.value, quantity.value);
  router.push('/cart');
};

onMounted(() => {
  loadProduct();
});
</script>
