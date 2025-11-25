<template>
  <AppCard hover @click="goToProduct">
    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200">
      <img
        :src="product.image || '/placeholder.jpg'"
        :alt="product.name"
        class="h-48 w-full object-cover object-center"
      />
    </div>

    <div class="mt-4">
      <h3 class="text-lg font-medium text-gray-900">{{ product.name }}</h3>
      <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ product.description }}</p>

      <div class="mt-4 flex items-center justify-between">
        <p class="text-xl font-bold text-gray-900">
          ${{ parseFloat(product.price).toFixed(2) }}
        </p>
        <p class="text-sm text-gray-500">
          Stock: {{ product.stock }}
        </p>
      </div>
    </div>

    <template #footer>
      <AppButton
        class="w-full"
        :disabled="product.stock === 0"
        @click.stop="addToCart"
      >
        {{ product.stock === 0 ? 'Out of Stock' : 'Add to Cart' }}
      </AppButton>
    </template>
  </AppCard>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { useCart } from '@/composables/useCart';
import AppCard from './AppCard.vue';
import AppButton from './AppButton.vue';

const props = defineProps({
  product: {
    type: Object,
    required: true,
  },
});

const router = useRouter();
const { addToCart: addItemToCart } = useCart();

const goToProduct = () => {
  router.push({ name: 'product-detail', params: { id: props.product.id } });
};

const addToCart = () => {
  addItemToCart(props.product);
};
</script>
