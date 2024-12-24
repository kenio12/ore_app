<template>
  <div class="login-container">
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <input
          v-model="email"
          type="email"
          required
          placeholder="メールアドレス"
        />
      </div>
      
      <div class="form-group">
        <input
          v-model="password"
          type="password"
          required
          placeholder="パスワード"
        />
      </div>
      
      <button type="submit" :disabled="isLoading">
        {{ isLoading ? 'ログイン中...' : 'ログイン' }}
      </button>
      
      <div v-if="error" class="error">
        {{ error }}
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const router = useRouter()
const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const error = ref('')
const isLoading = ref(false)

const handleSubmit = async () => {
  try {
    isLoading.value = true
    error.value = ''
    
    await authStore.login(email.value, password.value)
    router.push('/')
  } catch (err) {
    error.value = 'ログインに失敗しました'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
.login-container {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: #F7FAFC;
}

.login-box {
  background: white;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.title {
  text-align: center;
  margin-bottom: 2rem;
  font-size: 1.5rem;
  color: #2D3748;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #4A5568;
}

.form-input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #E2E8F0;
  border-radius: 0.375rem;
  font-size: 1rem;
}

.form-input:focus {
  outline: none;
  border-color: #4299E1;
  box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
}

.btn {
  width: 100%;
  padding: 0.5rem;
  border: none;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  margin-top: 1rem;
}

.btn-primary {
  background-color: #4299E1;
  color: white;
}

.btn-primary:hover {
  background-color: #3182CE;
}

.links {
  margin-top: 1rem;
  text-align: center;
}

.links a {
  color: #4299E1;
  text-decoration: none;
}

.links a:hover {
  text-decoration: underline;
}
</style> 