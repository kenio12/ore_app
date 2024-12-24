<template>
  <div class="post-app">
    <div class="header-section">
      <h1>アプリ投稿</h1>
      <p class="author">投稿者: {{ authStore.user?.username || '名前未設定' }}</p>
    </div>

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
        <label for="demo_url">アプリURL（任意）</label>
        <input 
          type="url" 
          id="demo_url" 
          v-model="appData.demo_url"
          placeholder="https://example.com"
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
          <option value="趣味・創作">趣味・創作</option>
          <option value="ゲーム">ゲーム</option>
          <option value="プログラミング">プログラミング</option>
          <option value="教育">教育</option>
          <option value="ビジネス">ビジネス</option>
          <option value="ユーケティング・宣伝">マーケティング・宣伝</option>
          <option value="ユーティリティ">ユーティリティ</option>
          <option value="その他">その他</option>
        </select>
      </div>

      <div class="form-group">
        <label for="app_type">アプリタイプ</label>
        <select id="app_type" v-model="appData.app_type" required>
          <option value="WEB_APP">Webアプリ</option>
          <option value="MOBILE_APP">モバイルアプリ</option>
          <option value="DESKTOP_APP">デスクトップアプリ</option>
          <option value="CLI_TOOL">CLIツール</option>
          <option value="GAME">ゲーム</option>
          <option value="OTHER">その他</option>
        </select>
      </div>

      <div class="tech-stack-section">
        <h3>技術スタック</h3>
        
        <div class="tech-stack-group">
          <h4>フロントエンド</h4>
          <div class="form-group">
            <label>言語</label>
            <select v-model="appData.frontend.language">
              <option value="JavaScript">JavaScript</option>
              <option value="TypeScript">TypeScript</option>
              <option value="HTML/CSS">HTML/CSS</option>
              <option value="Dart">Dart</option>
              <option value="その他">その他</option>
            </select>
            <input 
              v-if="appData.frontend.language === 'その他'"
              type="text"
              v-model="appData.frontend.customLanguage"
              placeholder="使用言語を入力"
            />
          </div>
          <div class="form-group">
            <label>フレームワーク</label>
            <select v-model="appData.frontend.framework">
              <option value="Vue.js">Vue.js</option>
              <option value="React">React</option>
              <option value="Next.js">Next.js</option>
              <option value="Nuxt">Nuxt</option>
              <option value="Angular">Angular</option>
              <option value="Svelte">Svelte</option>
              <option value="Flutter">Flutter</option>
              <option value="その他">その他</option>
            </select>
            <input 
              v-if="appData.frontend.framework === 'その他'"
              type="text"
              v-model="appData.frontend.customFramework"
              placeholder="フレームワークを入力"
            />
          </div>
          <div class="form-group">
            <label>選定理由</label>
            <textarea v-model="appData.frontend.reason"></textarea>
          </div>
        </div>

        <div class="tech-stack-group">
          <h4>バックエンド</h4>
          <div class="form-group">
            <label>言語</label>
            <select v-model="appData.backend.language">
              <option value="Python">Python</option>
              <option value="Node.js">Node.js</option>
              <option value="Ruby">Ruby</option>
              <option value="PHP">PHP</option>
              <option value="Go">Go</option>
              <option value="Java">Java</option>
              <option value="C#">C#</option>
              <option value="その他">その他</option>
            </select>
            <input 
              v-if="appData.backend.language === 'その他'"
              type="text"
              v-model="appData.backend.customLanguage"
              placeholder="使用言語を入力"
            />
          </div>
          <div class="form-group">
            <label>フレームワーク</label>
            <select v-model="appData.backend.framework">
              <option value="FastAPI">FastAPI</option>
              <option value="Django">Django</option>
              <option value="Flask">Flask</option>
              <option value="Express">Express</option>
              <option value="Ruby on Rails">Ruby on Rails</option>
              <option value="Laravel">Laravel</option>
              <option value="Spring Boot">Spring Boot</option>
              <option value="その他">その他</option>
            </select>
            <input 
              v-if="appData.backend.framework === 'その他'"
              type="text"
              v-model="appData.backend.customFramework"
              placeholder="フレームワークを入力"
            />
          </div>
          <div class="form-group">
            <label>選定理由</label>
            <textarea v-model="appData.backend.reason"></textarea>
          </div>
        </div>

        <div class="tech-stack-group">
          <h4>データベース</h4>
          <div class="form-group">
            <label>タイプ</label>
            <input type="text" v-model="appData.database.type" />
          </div>
          <div class="form-group">
            <label>ホスティング</label>
            <input type="text" v-model="appData.database.hosting" />
          </div>
          <div class="form-group">
            <label>選定理由</label>
            <textarea v-model="appData.database.reason"></textarea>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>スクリーンショット</label>
        <input 
          type="file" 
          @change="handleImageUpload" 
          multiple 
          accept="image/*"
        />
        <div class="preview-images">
          <!-- プレビュー表示領域 -->
        </div>
      </div>

      <div class="form-group">
        <label>企画のきっかけ</label>
        <textarea v-model="appData.story.motivation"></textarea>
      </div>
      <div class="form-group">
        <label>苦労した点</label>
        <textarea v-model="appData.story.challenges"></textarea>
      </div>
      <div class="form-group">
        <label>今後の展望</label>
        <textarea v-model="appData.story.future_plans"></textarea>
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
  prefix_icon: '����️',
  suffix_icon: '🏴‍☠️',
  screenshots: [],
  frontend: {
    language: '',
    customLanguage: '',
    framework: '',
    customFramework: '',
    hosting: 'Render',
    reason: ''
  },
  backend: {
    language: '',
    customLanguage: '',
    framework: '',
    customFramework: '',
    hosting: 'Render',
    reason: ''
  },
  database: {
    type: '',
    hosting: 'MONGODB_ATLAS',
    reason: ''
  },
  story: {
    motivation: '',
    challenges: '',
    future_plans: ''
  }
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

const handleImageUpload = async (event: Event) => {
  const files = (event.target as HTMLInputElement).files
  if (!files) return

  // TODO: Cloudinaryへのアップロード処理を実装
}
</script>

<style scoped>
.post-form {
  max-width: 800px;
  margin: 20px auto;
  padding: 0 20px;
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

.tech-stack-section {
  margin: 20px 0;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.tech-stack-group {
  margin: 15px 0;
}

.preview-images {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.header-section {
  text-align: center;
  margin-bottom: 30px;
  padding: 20px 0;
}

h1 {
  font-size: 2em;
  margin-bottom: 10px;
}

.author {
  font-size: 1.1em;
  color: #666;
}

.tech-stack-group select {
  width: 100%;
  padding: 8px;
  margin-bottom: 8px;
}

.tech-stack-group input[type="text"] {
  width: 100%;
  padding: 8px;
  margin-top: 4px;
}
</style> 