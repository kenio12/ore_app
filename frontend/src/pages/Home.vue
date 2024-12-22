<template>
  <div class="home-container">
    <div class="container">
      <div class="header">
        <h1 class="title">🗡️ 俺のアプリ 🏴‍☠️</h1>
        <p class="subtitle">アプリ王になる！</p>
      </div>

      <div class="apps-grid">
        <div v-for="app in apps" :key="app._id" class="app-card" :style="{ borderColor: AppTypeColors[app.genre || AppType.UNSPECIFIED] }">
          <h3 class="app-title">
            <span class="metadata-label">アプリ名：</span>
            {{ app.title }}
          </h3>

          <div class="app-info">
            <div class="app-genre">
              <span class="metadata-label">タイプ:</span>
              {{ AppTypeLabels[app.genre || AppType.UNSPECIFIED] }}
            </div>
            <div class="app-creator">作成者: {{ app.user?.display_name || app.user?.username || '不明なユーザー' }}</div>
          </div>

          <div class="screenshot-container">
            <img class="screenshot" :src="app.screenshots[0]" :alt="app.title" />
          </div>

          <div class="app-date">
            <span class="metadata-label">投稿日:</span>
            {{ formatDate(app.created_at) }}
          </div>
        </div>
      </div>

      <div v-if="authStore.isAuthenticated" class="account-actions">
        <button @click="handleDeleteAccount" class="delete-account-btn">
          退会する
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { AppType, AppTypeLabels, AppTypeColors } from '@/types/app'

const authStore = useAuthStore()
const router = useRouter()

interface App {
  _id: string
  title: string
  description: string
  genre?: AppType
  demo_url?: string
  github_url?: string
  screenshots: string[]
  created_at: string
  user?: {
    _id: string
    username: string
    display_name?: string
  }
}

const apps = ref<App[]>([])

onMounted(async () => {
  try {
    const response = await fetch('http://localhost:8000/api/apps/', {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    if (!response.ok) {
      throw new Error('アプリの取得に失敗しました')
    }
    
    const data = await response.json()
    // 新着順（降順）に並び替
    apps.value = data.sort((a: App, b: App) => {
      return new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
    })
    console.log('取得したアプリ:', apps.value)
  } catch (error) {
    console.error('アプリの取得に失敗しました:', error)
  }
})

const handleDeleteAccount = async () => {
  if (confirm('本当にアカウントを削除しますか？\n\n⚠️ この操作は取り消せません。\n- すべての投稿が削除されます\n- アカウント情報が完全に削除されます')) {
    try {
      await authStore.deleteAccount()
      router.push('/')
    } catch (error) {
      console.error('Account deletion failed:', error)
    }
  }
}

// 付フォーマット用の関数を追加
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('ja-JP', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}
</script>

<style scoped>
/* リセット用の基本スタイル */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.home-container {
  background-color: #EBF8FF;
  min-height: 100vh;
  width: 100%;
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  width: 100%;
  padding: 0 1rem;
}

.app-card {
  width: 100%;
  margin: 0 auto;
  background: white;
  border: 3px solid;
  border-radius: 0.5rem;
  padding: 1rem;
  margin-bottom: 1rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.screenshot-container {
  padding: 1rem;
  background: #F7FAFC;
}

.screenshot {
  max-width: 100%;
}

.header {
  text-align: center;
  margin: 2rem 0;
}

.title {
  font-size: 3rem;
}

.subtitle {
  font-size: 1.5rem;
  margin-top: 1rem;
}

.app-title {
  text-align: center;
  margin-bottom: 1rem;
}

.app-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.app-creator {
  margin-left: auto;
}

.app-date {
  text-align: right;
  margin-top: 1rem;
}

@media (max-width: 640px) {
  .screenshot-mobile {
    width: 60px;
  }
}
</style> 