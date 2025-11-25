<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Sign in to your account</h2>
        <p class="text-sm text-gray-600">
          Or
          <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
            create a new account
          </router-link>
        </p>
      </div>

      <AppCard class="mt-8">
        <form @submit.prevent="handleLogin">
          <div class="space-y-4">
            <AppInput
              v-model="form.email"
              label="Email"
              type="email"
              placeholder="your@email.com"
              required
              :error="errors.email"
            />

            <AppInput
              v-model="form.password"
              label="Password"
              type="password"
              placeholder="••••••••"
              required
              :error="errors.password"
            />
          </div>

          <div class="mt-6">
            <AppButton type="submit" class="w-full" :loading="loading">
              Sign in
            </AppButton>
          </div>

          <div v-if="errors.general" class="mt-4 text-sm text-red-600 text-center">
            {{ errors.general }}
          </div>
        </form>
      </AppCard>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '@/composables/useAuth';
import AppCard from '@/components/AppCard.vue';
import AppInput from '@/components/AppInput.vue';
import AppButton from '@/components/AppButton.vue';

const router = useRouter();
const { login } = useAuth();

const loading = ref(false);
const form = ref({
  email: '',
  password: '',
});
const errors = ref({});

const handleLogin = async () => {
  loading.value = true;
  errors.value = {};

  try {
    await login(form.value);
    router.push('/');
  } catch (error) {
    errors.value.general = error.response?.data?.message || 'Invalid credentials';
  } finally {
    loading.value = false;
  }
};
</script>
