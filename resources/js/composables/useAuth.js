import { ref, computed } from 'vue';
import { authService } from '@/services/api';

const user = ref(null);
const token = ref(localStorage.getItem('auth_token'));

export function useAuth() {
    const isAuthenticated = computed(() => !!token.value);
    const isAdmin = computed(() => user.value?.role === 'admin');

    const loadUser = async () => {
        if (!token.value) return;

        try {
            const response = await authService.getUser();
            user.value = response.data;
        } catch (error) {
            console.error('Failed to load user:', error);
            logout();
        }
    };

    const login = async (credentials) => {
        const response = await authService.login(credentials);
        token.value = response.data.token;
        user.value = response.data.user;
        localStorage.setItem('auth_token', token.value);
        localStorage.setItem('user', JSON.stringify(user.value));
    };

    const register = async (data) => {
        const response = await authService.register(data);
        token.value = response.data.token;
        user.value = response.data.user;
        localStorage.setItem('auth_token', token.value);
        localStorage.setItem('user', JSON.stringify(user.value));
    };

    const logout = () => {
        authService.logout().catch(() => {});
        token.value = null;
        user.value = null;
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
    };

    if (token.value && !user.value) {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            user.value = JSON.parse(storedUser);
        } else {
            loadUser();
        }
    }

    return {
        user,
        token,
        isAuthenticated,
        isAdmin,
        login,
        register,
        logout,
        loadUser,
    };
}
