<template>
  <div>
    <AppNavbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Products</h1>

      <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
          <AppInput
            v-model="searchQuery"
            placeholder="Search products..."
            @update:modelValue="handleSearch"
          />
        </div>

        <div class="w-full sm:w-64">
          <select
            v-model="selectedCategory"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            @change="handleCategoryChange"
          >
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
        </div>
      </div>

      <AppLoading v-if="loading" message="Loading products..." />

      <div v-else-if="products.length === 0" class="text-center py-12">
        <p class="text-gray-500">No products found</p>
      </div>

      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <ProductCard v-for="product in products" :key="product.id" :product="product" />
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { productService, categoryService } from '@/services/api';
import AppNavbar from '@/components/AppNavbar.vue';
import AppInput from '@/components/AppInput.vue';
import AppLoading from '@/components/AppLoading.vue';
import ProductCard from '@/components/ProductCard.vue';

const loading = ref(true);
const products = ref([]);
const categories = ref([]);
const searchQuery = ref('');
const selectedCategory = ref('');

const loadProducts = async () => {
  loading.value = true;
  try {
    const params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedCategory.value) params.category_id = selectedCategory.value;

    const response = await productService.getAll(params);
    products.value = response.data;
  } catch (error) {
    console.error('Error loading products:', error);
  } finally {
    loading.value = false;
  }
};

const loadCategories = async () => {
  try {
    const response = await categoryService.getAll();
    categories.value = response.data;
  } catch (error) {
    console.error('Error loading categories:', error);
  }
};

const handleSearch = () => {
  loadProducts();
};

const handleCategoryChange = () => {
  loadProducts();
};

onMounted(() => {
  loadCategories();
  loadProducts();
});
</script>
