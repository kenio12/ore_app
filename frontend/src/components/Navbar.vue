<template>
  <nav class="navbar" :class="{ 'navbar-hidden': !showNavbar }">
    <div class="container">
      <router-link to="/" class="brand">
        <span class="brand-text">
          <span class="brand-full-text">俺のアプリ</span>
          <button class="mobile-brand-btn">
            <span class="mobile-brand-text">ア</span>
          </button>
        </span>
      </router-link>

      <div class="nav-buttons">
        <router-link v-if="authStore.isAuthenticated" to="/post-app">
          <button class="btn btn-primary">
            <span class="btn-text">アプリを投稿</span>
            <span class="mobile-text">投</span>
          </button>
        </router-link>

        <template v-if="authStore.isAuthenticated">
          <button @click="handleLogout" class="btn btn-red">
            <span class="btn-text">ログアウト</span>
            <span class="mobile-text">出</span>
          </button>
        </template>
        <template v-else>
          <router-link to="/login">
            <button class="btn btn-blue">
              <span class="btn-text">ログイン</span>
              <span class="mobile-text">入</span>
            </button>
          </router-link>
          <router-link to="/signup">
            <button class="btn btn-green">
              <span class="btn-text">新規登録</span>
              <span class="mobile-text">新</span>
            </button>
          </router-link>
        </template>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

// スクロール関連の変数
let lastScrollY = 0
const showNavbar = ref(true)

// スクロールハンドラー
const handleScroll = () => {
  const currentScrollY = window.scrollY
  
  // 上にスクロールした時はナビバーを表示
  // 下にスクロールした時は隠す
  // ただし、一番上にいる時は常に表示
  if (currentScrollY <= 0) {
    showNavbar.value = true
  } else {
    showNavbar.value = currentScrollY < lastScrollY
  }
  
  lastScrollY = currentScrollY
}

// コンポーネントがマウントされた時にイベントリスナーを追加
onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
})

// コンポーネントがアンマウントされた時にイベントリスナーを削除
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})

const handleLogout = async () => {
  try {
    await authStore.logout()
    router.push('/')
  } catch (error) {
    console.error('Logout failed:', error)
  }
}
</script>

<style scoped>
.navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background-color: #2B6CB0;
  padding: 0.5rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  transition: transform 0.3s ease;
  z-index: 1000;
}

.navbar-hidden {
  transform: translateY(-100%);
}

:root {
  --navbar-height: 3rem;
}

.container {
  height: 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 0.5rem;
}

.brand {
  text-decoration: none;
  transition: opacity 0.2s;
}

.brand:hover {
  opacity: 0.9;
}

.brand-text {
  font-weight: bold;
  font-size: 1.1rem;
  color: white;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.brand-icon {
  font-size: 1.5rem;
  cursor: pointer;
}

.brand-full-text {
  font-weight: bold;
  font-size: 1.25rem;
  color: white;
}

.nav-buttons {
  display: flex;
  gap: 0.5rem;
}

.btn {
  padding: 0.25rem 0.5rem;
  border-radius: 0.375rem;
  font-weight: 500;
  cursor: pointer;
  border: none;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.875rem;
}

.btn-green {
  background-color: #48BB78;
  color: white;
}

.btn-blue {
  background-color: #3182CE;
  color: white;
}

.btn-red {
  background-color: #E53E3E;
  color: white;
}

.btn-green:hover {
  background-color: #38A169;
}

.btn-blue:hover {
  background-color: #2B6CB0;
}

.btn-red:hover {
  background-color: #C53030;
}

.btn-icon {
  font-size: 1.2rem;
  display: none;
}

@media (max-width: 640px) {
  .btn-text {
    display: none;
  }

  .btn-icon {
    display: block;
  }

  .btn {
    padding: 0.5rem;
    min-width: 2.5rem;
    justify-content: center;
  }

  .nav-buttons {
    gap: 0.5rem;
  }

  .brand-full-text {
    display: none;
  }
  
  .brand-text {
    gap: 0.25rem;
  }

  .brand-icon {
    font-size: 1.4rem;
  }
}

.btn-primary {
  background-color: #805AD5;
  color: white;
}

.btn-primary:hover {
  background-color: #6B46C1;
}

.nav-buttons a {
  text-decoration: none;
}

.brand {
  text-decoration: none;
  transition: opacity 0.2s;
}

.brand:hover {
  opacity: 0.9;
}

.mobile-brand-btn {
  display: none;
  background-color: #4A5568;
  color: white;
  border: none;
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
  font-weight: bold;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.2s;
  align-items: center;
  gap: 0.5rem;
}

.mobile-brand-btn:hover {
  background-color: #2D3748;
}

.mobile-brand-text {
  display: inline-block;
  vertical-align: middle;
}

@media (max-width: 640px) {
  .brand-full-text {
    display: none;
  }

  .mobile-brand-btn {
    display: flex;
  }

  .brand-text {
    gap: 0.25rem;
  }

  .brand-icon {
    font-size: 1.2rem;
  }
}

.mobile-text {
  display: none;
}

@media (max-width: 640px) {
  .btn-text {
    display: none;
  }

  .mobile-text {
    display: block;
  }

  .btn-icon {
    display: none;
  }
}
</style> 