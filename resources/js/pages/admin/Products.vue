<template>
  <div>
    <AppNavbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Manage Products</h1>
        <AppButton @click="showCreateModal = true">Add New Product</AppButton>
      </div>

      <AppLoading v-if="loading" message="Loading products..." />

      <div v-else class="bg-white shadow overflow-hidden sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Image</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in products" :key="product.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <img :src="product.image || '/placeholder.jpg'" :alt="product.name" class="h-10 w-10 rounded object-cover" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">{{ product.category?.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${{ parseFloat(product.price).toFixed(2) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ product.stock }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <AppButton size="sm" variant="secondary" class="mr-2" @click="editProduct(product)">
                  Edit
                </AppButton>
                <AppButton size="sm" variant="danger" @click="deleteProduct(product.id)">
                  Delete
                </AppButton>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>

    <AppModal v-model="showCreateModal" title="Add New Product">
      <form @submit.prevent="handleCreateProduct">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select
              v-model="form.category_id"
              required
              class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select category</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <AppInput v-model="form.name" label="Name" required />
          <AppInput v-model="form.slug" label="Slug" required />
          <AppInput v-model="form.description" label="Description" />
          <AppInput v-model="form.price" label="Price" type="number" step="0.01" required />
          <AppInput v-model="form.stock" label="Stock" type="number" required />
          <AppInput v-model="form.image" label="Image URL" />
        </div>

        <div class="mt-6 flex justify-end gap-2">
          <AppButton type="button" variant="secondary" @click="showCreateModal = false">
            Cancel
          </AppButton>
          <AppButton type="submit" :loading="submitting">
            Create Product
          </AppButton>
        </div>
      </form>
    </AppModal>

    <AppModal v-model="showEditModal" title="Edit Product">
      <form @submit.prevent="handleUpdateProduct">
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select
              v-model="form.category_id"
              required
              class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select category</option>
              <option v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </option>
            </select>
          </div>

          <AppInput v-model="form.name" label="Name" required />
          <AppInput v-model="form.slug" label="Slug" required />
          <AppInput v-model="form.description" label="Description" />
          <AppInput v-model="form.price" label="Price" type="number" step="0.01" required />
          <AppInput v-model="form.stock" label="Stock" type="number" required />
          <AppInput v-model="form.image" label="Image URL" />
        </div>

        <div class="mt-6 flex justify-end gap-2">
          <AppButton type="button" variant="secondary" @click="showEditModal = false">
            Cancel
          </AppButton>
          <AppButton type="submit" :loading="submitting">
            Update Product
          </AppButton>
        </div>
      </form>
    </AppModal>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import { productService, categoryService } from '@/services/api';
import AppNavbar from '@/components/AppNavbar.vue';
import AppButton from '@/components/AppButton.vue';
import AppInput from '@/components/AppInput.vue';
import AppModal from '@/components/AppModal.vue';
import AppLoading from '@/components/AppLoading.vue';

const loading = ref(true);
const submitting = ref(false);
const products = ref([]);
const categories = ref([]);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingProduct = ref(null);

const form = reactive({
  category_id: '',
  name: '',
  slug: '',
  description: '',
  price: '',
  stock: '',
  image: '',
});

const resetForm = () => {
  form.category_id = '';
  form.name = '';
  form.slug = '';
  form.description = '';
  form.price = '';
  form.stock = '';
  form.image = '';
};

const loadProducts = async () => {
  try {
    const response = await productService.getAll();
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

const handleCreateProduct = async () => {
  submitting.value = true;
  try {
    await productService.create(form);
    showCreateModal.value = false;
    resetForm();
    loadProducts();
  } catch (error) {
    console.error('Error creating product:', error);
    alert(error.response?.data?.message || 'Failed to create product');
  } finally {
    submitting.value = false;
  }
};

const editProduct = (product) => {
  editingProduct.value = product;
  form.category_id = product.category_id;
  form.name = product.name;
  form.slug = product.slug;
  form.description = product.description;
  form.price = product.price;
  form.stock = product.stock;
  form.image = product.image;
  showEditModal.value = true;
};

const handleUpdateProduct = async () => {
  submitting.value = true;
  try {
    await productService.update(editingProduct.value.id, form);
    showEditModal.value = false;
    resetForm();
    editingProduct.value = null;
    loadProducts();
  } catch (error) {
    console.error('Error updating product:', error);
    alert(error.response?.data?.message || 'Failed to update product');
  } finally {
    submitting.value = false;
  }
};

const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete this product?')) return;

  try {
    await productService.delete(id);
    loadProducts();
  } catch (error) {
    console.error('Error deleting product:', error);
    alert(error.response?.data?.message || 'Failed to delete product');
  }
};

onMounted(() => {
  loadCategories();
  loadProducts();
});
</script>
