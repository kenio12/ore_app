<template>
  <div class="post-app">
    <h1>アプリ投稿</h1>
    <p>投稿者: {{ authStore.user?.email }}</p>

    <form @submit.prevent="handleSubmit" class="post-form">
      <div class="form-group">
        <label for="title">アプリ名</label>
        <input 
          type="text" 
          id="title" 
          v-model="appData.title" 
          required
        />
      </div>

      <div class="form-group">
        <label for="description">説明</label>
        <textarea 
          id="description" 
          v-model="appData.description" 
          required
        ></textarea>
      </div>

      <div class="form-group">
        <label for="demo_url">デモURL</label>
        <input 
          type="url" 
          id="demo_url" 
          v-model="appData.demo_url"
        />
      </div>

      <div class="form-group">
        <label for="github_url">GitHub URL</label>
        <input 
          type="url" 
          id="github_url" 
          v-model="appData.github_url"
        />
      </div>

      <div class="form-group">
        <label for="genre">ジャンル（用途）</label>
        <select 
          id="genre" 
          v-model="appData.genres[0]" 
          required
        >
          <option value="生産性・仕事効率化">生産性・仕事効率化</option>
          <option value="エンターテイメント">エンターテイメント</option>
          <option value="ゲーム">ゲーム</option>
          <option value="プログラミング">プログラミング</option>
          <option value="教育">教育</option>
          <option value="ビジネス">ビジネス</option>
          <option value="ユーティリティ">ユーティリティ</option>
          <option value="その他">その他</option>
        </select>
      </div>

      <button type="submit">投稿する</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/utils/api'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const error = ref('')

const appData = ref({
  title: '',
  description: '',
  demo_url: '',
  github_url: '',
  app_type: 'WEB_APP',
  status: 'IN_DEVELOPMENT',
  genres: ['ゲーム'],
  custom_genres: [],
  prefix_icon: '🗡️',
  suffix_icon: '🏴‍☠️',
  screenshots: []
})

// ジャンルの対応表を逆にする（英語→日本語）
const genreMapping: { [key: string]: string } = {
  'PRODUCTIVITY': '生産性・仕事効率化',
  'LIFESTYLE': 'ライフスタイル',
  'HEALTH': '健康・フィットネス',
  'FINANCE': '金融・家計',
  'ENTERTAINMENT': 'エンターテイメント',
  'GAME': 'ゲーム',
  'PROGRAMMING': 'プログラミング',
  'BUSINESS': 'ビジネス',
  'UTILITY': 'ユーティリティ',
  'CUSTOM': 'その他'
}

const router = useRouter()

const handleSubmit = async () => {
  try {
    const sendData = {
      ...appData.value
    }
    console.log('送信データの詳細:', JSON.stringify(sendData, null, 2))

    const response = await api.post('/api/apps/', sendData)

    if (response.status === 200) {
      // 成功メッセージを表示
      alert('アプリを投稿しました！')
      
      // ホーム画面に遷移
      router.push('/')
    }
  } catch (e: any) {
    error.value = 'アプリの投稿に失敗しました'
    alert('アプリの投稿に失敗しました')
    console.error('送信データ:', JSON.stringify(appData.value, null, 2))
    if (e.response?.data?.detail) {
      console.error('バリデーションエラーの詳細:', JSON.stringify(e.response.data.detail, null, 2))
    }
  }
}
</script>

<style scoped>
.post-form {
  max-width: 600px;
  margin: 20px auto;
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
}

input[type="text"],
input[type="url"],
textarea {
  width: 100%;
  padding: 8px;
}

textarea {
  height: 150px;
}

.error {
  color: red;
  margin-top: 10px;
}
</style> 