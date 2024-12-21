<template>
  <div class="login-container">
    <div class="login-box">
      <h1 class="title">ログイン</h1>
      
      <form @submit.prevent="handleSubmit">
        <div class="form-group">
          <label for="email">メールアドレス</label>
          <input
            id="email"
            v-model="formData.email"
            type="email"
            required
            class="form-input"
            placeholder="example@example.com"
          />
        </div>
        
        <div class="form-group">
          <label for="password">パスワード</label>
          <input
            id="password"
            v-model="formData.password"
            type="password"
            required
            class="form-input"
            placeholder="パスワードを入力"
          />
        </div>
        
        <button type="submit" class="btn btn-primary">
          ログイン
        </button>
      </form>
      
      <div class="links">
        <router-link to="/signup">アカウントをお持ちでない方</router-link>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()

const formData = ref({
  email: '',
  password: ''
})

const handleSubmit = async () => {
  try {
    if (!formData.value.email || !formData.value.password) {
      alert('メールアドレスとパスワードを入力してください')
      return
    }

    console.log('送信するデータ:', formData.value)
    
    await authStore.login(formData.value)
    
    const redirectPath = route.query.redirect as string
    router.push(redirectPath || '/')
    
  } catch (error) {
    console.error('ログインエラー:', error)
    alert(error instanceof Error ? error.message : 'ログインに失敗しました')
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