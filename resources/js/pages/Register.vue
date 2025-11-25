<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full">
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Create your account</h2>
        <p class="text-sm text-gray-600">
          Already have an account?
          <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
            Sign in
          </router-link>
        </p>
      </div>

      <AppCard class="mt-8">
        <form @submit.prevent="handleRegister">
          <div class="space-y-4">
            <AppInput
              v-model="form.name"
              label="Name"
              placeholder="John Doe"
              required
              :error="errors.name"
            />

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

            <AppInput
              v-model="form.password_confirmation"
              label="Confirm Password"
              type="password"
              placeholder="••••••••"
              required
              :error="errors.password_confirmation"
            />
          </div>

          <div class="mt-6">
            <AppButton type="submit" class="w-full" :loading="loading">
              Register
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
const { register } = useAuth();

const loading = ref(false);
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
});
const errors = ref({});

const handleRegister = async () => {
  loading.value = true;
  errors.value = {};

  try {
    await register(form.value);
    router.push('/');
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    } else {
      errors.value.general = error.response?.data?.message || 'Registration failed';
    }
  } finally {
    loading.value = false;
  }
};
</script>
