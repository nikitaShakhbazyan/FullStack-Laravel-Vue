<template>
  <div>
    <AppNavbar />

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Manage Orders</h1>

      <AppLoading v-if="loading" message="Loading orders..." />

      <div v-else class="space-y-4">
        <AppCard v-for="order in orders" :key="order.id">
          <template #header>
            <div class="flex justify-between items-center">
              <div>
                <h3 class="text-lg font-medium">Order #{{ order.id }}</h3>
                <p class="text-sm text-gray-500">
                  Customer: {{ order.user?.name }} ({{ order.user?.email }})
                </p>
                <p class="text-sm text-gray-500">
                  Date: {{ new Date(order.created_at).toLocaleString() }}
                </p>
              </div>
              <div class="flex items-center gap-4">
                <select
                  v-model="order.status"
                  @change="updateOrderStatus(order)"
                  class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                  <option value="pending">Pending</option>
                  <option value="processing">Processing</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
                <span :class="statusClass(order.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                  {{ order.status }}
                </span>
              </div>
            </div>
          </template>

          <div class="space-y-3">
            <div v-for="item in order.order_items" :key="item.id" class="flex items-center gap-4">
              <img
                :src="item.product?.image || '/placeholder.jpg'"
                :alt="item.product?.name"
                class="w-16 h-16 object-cover rounded"
              />
              <div class="flex-1">
                <p class="font-medium">{{ item.product?.name }}</p>
                <p class="text-sm text-gray-500">
                  {{ item.quantity }} × ${{ parseFloat(item.price).toFixed(2) }}
                </p>
              </div>
              <p class="font-medium">${{ (item.price * item.quantity).toFixed(2) }}</p>
            </div>
          </div>

          <template #footer>
            <div class="flex justify-between items-center">
              <span class="text-lg font-bold">Total: ${{ parseFloat(order.total_amount).toFixed(2) }}</span>
            </div>
          </template>
        </AppCard>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { orderService } from '@/services/api';
import AppNavbar from '@/components/AppNavbar.vue';
import AppCard from '@/components/AppCard.vue';
import AppLoading from '@/components/AppLoading.vue';

const loading = ref(true);
const orders = ref([]);

const statusClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    completed: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800',
  };
  return classes[status] || 'bg-gray-100 text-gray-800';
};

const loadOrders = async () => {
  try {
    const response = await orderService.getAll();
    orders.value = response.data;
  } catch (error) {
    console.error('Error loading orders:', error);
  } finally {
    loading.value = false;
  }
};

const updateOrderStatus = async (order) => {
  try {
    await orderService.updateStatus(order.id, order.status);
  } catch (error) {
    console.error('Error updating order status:', error);
    alert('Failed to update order status');
    loadOrders();
  }
};

onMounted(() => {
  loadOrders();
});
</script>
