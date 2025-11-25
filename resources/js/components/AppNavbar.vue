<template>
  <nav class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex">
          <router-link to="/" class="flex items-center text-xl font-bold text-blue-600">
            Online Shop
          </router-link>

          <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
            <router-link
              to="/products"
              class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 hover:text-blue-600"
            >
              Products
            </router-link>

            <router-link
              v-if="isAdmin"
              to="/admin"
              class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-900 hover:text-blue-600"
            >
              Admin Panel
            </router-link>
          </div>
        </div>

        <div class="flex items-center space-x-4">
          <router-link to="/cart" class="relative p-2 text-gray-600 hover:text-blue-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span
              v-if="itemCount > 0"
              class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full"
            >
              {{ itemCount }}
            </span>
          </router-link>

          <template v-if="isAuthenticated">
            <router-link
              to="/orders"
              class="text-sm font-medium text-gray-900 hover:text-blue-600"
            >
              My Orders
            </router-link>
            <AppButton size="sm" variant="secondary" @click="handleLogout">
              Logout
            </AppButton>
          </template>
          <template v-else>
            <router-link to="/login">
              <AppButton size="sm" variant="secondary">
                Login
              </AppButton>
            </router-link>
            <router-link to="/register">
              <AppButton size="sm">
                Register
              </AppButton>
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useAuth } from '@/composables/useAuth';
import { useCart } from '@/composables/useCart';
import { useRouter } from 'vue-router';
import AppButton from './AppButton.vue';

const { isAuthenticated, isAdmin, logout } = useAuth();
const { itemCount } = useCart();
const router = useRouter();

const handleLogout = () => {
  logout();
  router.push('/');
};
</script>
