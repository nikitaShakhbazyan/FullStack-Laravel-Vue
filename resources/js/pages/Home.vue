<template>
  <div>
    <AppNavbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to Online Shop</h1>
        <p class="text-xl text-gray-600 mb-8">Find the best products at great prices</p>
        <router-link to="/products">
          <AppButton size="lg">Browse Products</AppButton>
        </router-link>
      </div>

      <AppLoading v-if="loading" message="Loading featured products..." />

      <div v-else>
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Featured Products</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <ProductCard v-for="product in featuredProducts" :key="product.id" :product="product" />
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { productService } from '@/services/api';
import AppNavbar from '@/components/AppNavbar.vue';
import AppButton from '@/components/AppButton.vue';
import AppLoading from '@/components/AppLoading.vue';
import ProductCard from '@/components/ProductCard.vue';

const loading = ref(true);
const featuredProducts = ref([]);

onMounted(async () => {
  try {
    const response = await productService.getAll();
    featuredProducts.value = response.data.slice(0, 8);
  } catch (error) {
    console.error('Error loading featured products:', error);
  } finally {
    loading.value = false;
  }
});
</script>
