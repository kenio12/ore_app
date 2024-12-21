<template>
  <div class="home-container">
    <div class="container">
      <div class="header">
        <h1 class="title">🗡️ 俺のアプリ 🏴‍☠️</h1>
        <p class="subtitle">アプリ王になる！</p>
      </div>

      <div class="apps-grid">
        <div v-for="app in apps" :key="app._id" class="app-card">
          <div class="app-metadata">
            <h3 class="app-title">
              <span class="metadata-label hide-on-mobile">アプリ名：</span>
              {{ app.title }}
            </h3>
            <div 
              class="app-genre"
              :style="{
                backgroundColor: AppTypeColors[app.genre || AppType.UNSPECIFIED].bg,
                color: AppTypeColors[app.genre || AppType.UNSPECIFIED].text
              }"
            >
              <span class="metadata-label hide-on-mobile">タイプ:</span>
              {{ AppTypeLabels[app.genre || AppType.UNSPECIFIED] }}
            </div>
          </div>

          <div 
            class="card-accent"
            :style="{ backgroundColor: AppTypeColors[app.genre || AppType.UNSPECIFIED].border }"
          ></div>

          <div v-if="app.screenshots?.length" class="screenshot-container">
            <img
              :src="app.screenshots[0]"
              :alt="`${app.title}のスクリーンショット`"
              class="screenshot"
            />
          </div>

          <div class="app-footer">
            <p class="app-creator">作成者: {{ app.user?.display_name || app.user?.username || '不明なユーザー' }}</p>
            <div class="app-date">
              <span class="metadata-label hide-on-mobile">投稿日:</span>
              {{ formatDate(app.created_at) }}
            </div>
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
.home-container {
  background-color: #EBF8FF;
  min-height: 100vh;
  padding: 1.5rem 0;
}

.container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 1rem;
}

.header {
  text-align: center;
  margin-bottom: 1rem;
  padding: 0.5rem 0;
}

.title {
  font-size: 2.5rem;
  font-weight: bold;
  color: #2D3748;
  margin-bottom: 0.5rem;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

.subtitle {
  font-size: 1.25rem;
  color: #4A5568;
}

.apps-grid {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  padding: 0.5rem 1rem;
  max-width: 800px;
  margin: 0 auto;
}

.app-card {
  padding: 1rem;
  border-radius: 0.75rem;
  background: white;
  transition: all 0.3s ease;
  border: 1px solid #E2E8F0;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  position: relative;
  overflow: hidden;
}

.app-card::before {
  content: none;
}

.app-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
  border-color: #CBD5E0;
}

.screenshot-container {
  margin-top: 1rem;
  margin-bottom: 1rem;
  border-radius: 0.5rem;
  overflow: hidden;
  position: relative;
  width: 100%;
  background: #F7FAFC;
  display: flex;
  justify-content: center;
  align-items: center;
}

.screenshot {
  width: 100%;
  height: auto;
  object-fit: contain;
  max-width: 100%;
  max-height: 400px;
  display: block;
  transition: transform 0.3s ease;
}

.screenshot:hover {
  transform: scale(1.02);
}

.app-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 1.5rem;
  margin-top: 0.5rem;
}

.app-title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #2D3748;
  margin: 0;
}

.app-creator {
  font-size: 0.875rem;
  color: #718096;
  margin: 0;
}

.app-date {
  font-size: 0.8rem;
  color: #718096;
  background-color: #F7FAFC;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.account-actions {
  margin-top: 4rem;
  padding: 2rem 0;
  text-align: right;
  border-top: 1px solid #E2E8F0;
}

.delete-account-btn {
  background: none;
  border: none;
  color: #718096;
  font-size: 0.8rem;
  padding: 0.5rem 1rem;
  cursor: pointer;
  transition: all 0.2s;
  opacity: 0.85;
}

.delete-account-btn:hover {
  color: #E53E3E;
  text-decoration: underline;
  opacity: 1;
}

@media (max-width: 640px) {
  .account-actions {
    margin-top: 3rem;
    padding: 1.5rem 0.5rem;
  }
  
  .delete-account-btn {
    font-size: 0.75rem;
  }

  .hide-on-mobile {
    display: none;
  }

  .app-metadata, .app-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
  
  .app-title {
    font-size: 1rem;
  }
}

.app-metadata {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 1.5rem;
  position: relative;
}

.metadata-label {
  font-weight: 600;
  margin-right: 0.25rem;
  color: #718096;
}

.card-accent {
  position: absolute;
  top: 0;
  left: 0;
  width: 4px;
  height: 100%;
  border-radius: 4px 0 0 4px;
}
</style> 