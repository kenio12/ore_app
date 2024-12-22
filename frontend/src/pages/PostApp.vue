<template>
  <div class="post-app-container">
    <div class="container">
      <h1 class="title">アプリを紹介する</h1>

      <div class="form-box">
        <form @submit.prevent="handleSubmit">
          <div class="form-control">
            <label class="form-label">アプリ名</label>
            <input
              v-model="formData.name"
              type="text"
              class="form-input"
              placeholder="あなたのアプリの名前"
              required
            />
          </div>

          <div class="form-control">
            <label class="form-label">説明</label>
            <textarea
              v-model="formData.description"
              class="form-input"
              rows="6"
              placeholder="アプリの説明、開発秘話、苦労した点など"
              required
            />
          </div>

          <div class="form-control">
            <label class="form-label">アプリのURL</label>
            <input
              v-model="formData.demoUrl"
              type="url"
              class="form-input"
              placeholder="https://..."
            />
          </div>

          <div class="form-control">
            <label class="form-label">ソースコードURL</label>
            <input
              v-model="formData.sourceUrl"
              type="url"
              class="form-input"
              placeholder="https://github.com/..."
            />
          </div>

          <div class="form-control">
            <label class="form-label">アプリタイプ</label>
            <select
              v-model="formData.app_type"
              class="form-input"
              required
            >
              <option v-for="(label, type) in AppTypeLabels" 
                      :key="type" 
                      :value="type"
              >
                {{ label }}
              </option>
            </select>
          </div>

          <div class="form-control">
            <label class="form-label">
              スクリーンショット（1-3枚）<span class="required">*</span>
              <span class="hint">※ ホーム画面には最初の1-2枚が表示されます</span>
            </label>
            <div
              class="dropzone"
              @dragover.prevent
              @drop.prevent="handleDrop"
              @click="$refs.fileInput.click()"
            >
              <input
                ref="fileInput"
                type="file"
                multiple
                accept="image/*"
                class="hidden-input"
                @change="handleFileSelect"
              />
              <div class="dropzone-content">
                <i class="upload-icon">📤</i>
                <p>ドラッグ&ドロップ または クリックして画像を選択</p>
              </div>
            </div>

            <!-- プレビュー -->
            <div v-if="previews.length" class="preview-grid">
              <div v-for="(preview, index) in previews" :key="index" class="preview-item">
                <img :src="preview" :alt="`Preview ${index + 1}`" class="preview-image" />
                <button
                  @click="removeImage(index)"
                  class="remove-button"
                  type="button"
                >
                  ✕
                </button>
              </div>
            </div>
          </div>

          <div class="button-group">
            <button
              type="submit"
              class="btn btn-primary"
              :disabled="isSubmitting"
            >
              {{ isSubmitting ? '投稿中...' : '投稿する' }}
            </button>
            <router-link to="/" class="btn btn-secondary">
              キャンセル
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { AppType, AppTypeLabels } from '@/types/app'

const router = useRouter()
const authStore = useAuthStore()

// 下書きのキー名を定数化
const DRAFT_KEY = 'appPostDraft'

// 自動保存の間隔（ミリ秒）
const AUTO_SAVE_INTERVAL = 900000  // 15分 (1000ミリ秒 × 60秒 × 15分)

// 自動保存のタイマーID
let autoSaveTimer: number

// フォームデータを自動保存
const autoSaveForm = () => {
  const draftData = {
    ...formData.value,
    lastSaved: new Date().toISOString(),
    screenshots: [] // Fileオブジェクトは保存できないので除外
  }
  localStorage.setItem(DRAFT_KEY, JSON.stringify(draftData))
  console.log('下書きを保存しました:', new Date().toLocaleTimeString())
}

// コンポ��ネントのマウント時
onMounted(() => {
  // ログインチェック
  if (!authStore.isAuthenticated) {
    router.push({
      name: 'Login',
      query: { redirect: '/post' }
    })
    return
  }

  // 下書きの確認と復元
  const savedDraft = localStorage.getItem(DRAFT_KEY)
  if (savedDraft) {
    const draft = JSON.parse(savedDraft)
    const lastSaved = new Date(draft.lastSaved)
    const timeDiff = Math.round((Date.now() - lastSaved.getTime()) / (1000 * 60)) // 分単位

    if (confirm(`${timeDiff}分前の下書きがあります。復元しますか？`)) {
      // スクリーンショット以外のデータを復元
      formData.value = {
        ...draft,
        screenshots: [] // スクリーンショットは新規に追加してもらう
      }
    } else {
      // 下書きを削除
      localStorage.removeItem(DRAFT_KEY)
    }
  }

  // 自動保存を開始
  autoSaveTimer = window.setInterval(autoSaveForm, AUTO_SAVE_INTERVAL)
})

// コンポーネントのアンマウント時
onUnmounted(() => {
  // 自動保存を停止
  if (autoSaveTimer) {
    clearInterval(autoSaveTimer)
  }
})

const isSubmitting = ref(false)
const fileInput = ref<HTMLInputElement | null>(null)

const formData = ref({
  name: '',
  prefix_icon: '🗡️',
  suffix_icon: '🏴‍☠️',
  description: '',
  demoUrl: '',
  sourceUrl: '',
  screenshots: [] as File[],
  app_type: AppType.UNSPECIFIED
})

const previews = ref<string[]>([])

const handleFileSelect = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (input.files) {
    handleFiles(Array.from(input.files))
  }
}

const handleDrop = (event: DragEvent) => {
  const files = Array.from(event.dataTransfer?.files || [])
  if (files.some(file => !file.type.startsWith('image/'))) {
    alert('画像ファイルのみアップロードできます')
    return
  }
  handleFiles(files)
}

const handleFiles = (files: File[]) => {
  // 既存の画像と新しい画像の合計が3枚を超える場合はアラート
  if (formData.value.screenshots.length + files.length > 3) {
    alert('スクリーンショットは最大3枚までアップロードできます')
    return
  }
  
  formData.value.screenshots = [...formData.value.screenshots, ...files]
  
  // プレビューURLの生成
  files.forEach(file => {
    const url = URL.createObjectURL(file)
    previews.value.push(url)
  })
}

const removeImage = (index: number) => {
  // プレビューURLの解放
  URL.revokeObjectURL(previews.value[index])
  
  // 配列から削除
  previews.value.splice(index, 1)
  formData.value.screenshots.splice(index, 1)
}

const handleSubmit = async () => {
  try {
    // バリデーションチェック
    if (formData.value.screenshots.length === 0) {
      alert('スクリーンショットを1-3枚アップロードしてください')
      return
    }
    if (formData.value.screenshots.length > 3) {
      alert('スクリーンショットは最大3枚までです')
      return
    }

    isSubmitting.value = true

    // まず画像をアップロード
    const imageFormData = new FormData()
    formData.value.screenshots.forEach(file => {
      imageFormData.append('files', file)
    })

    const uploadResponse = await fetch('http://localhost:8000/api/upload/screenshots', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: imageFormData
    })

    if (!uploadResponse.ok) {
      throw new Error('画像のアップロードに失敗しました')
    }

    const uploadedUrls = await uploadResponse.json()

    // アプリデータを送信
    const postData = {
      title: formData.value.name,
      description: formData.value.description,
      demo_url: formData.value.demoUrl || null,
      github_url: formData.value.sourceUrl || null,
      screenshots: uploadedUrls,
      prefix_icon: formData.value.prefix_icon,
      suffix_icon: formData.value.suffix_icon,
      app_type: formData.value.app_type
    }

    console.log('送信するデータ:', postData)

    const response = await fetch('http://localhost:8000/api/apps/', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(postData)
    })

    if (!response.ok) {
      throw new Error('アプリの投稿に失敗しました')
    }

    // 投稿成功時に下書きを削除
    localStorage.removeItem(DRAFT_KEY)
    alert('投稿が完了しました！')
    router.push('/')

  } catch (error) {
    console.error('投稿エラー:', error)
    alert(error instanceof Error ? error.message : 'アプリの投稿に失敗しました')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
.post-app-container {
  background-color: var(--color-background);
  min-height: 100vh;
  padding: 2.5rem 0;
}

.title {
  font-size: 2xl;
  text-align: center;
  margin-bottom: 2rem;
}

.form-box {
  background: white;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  max-width: 800px;
  margin: 0 auto;
}

.dropzone {
  border: 2px dashed var(--color-primary);
  border-radius: 0.5rem;
  padding: 2rem;
  text-align: center;
  cursor: pointer;
  background-color: #f8fafc;
}

.dropzone:hover {
  background-color: #f1f5f9;
}

.hidden-input {
  display: none;
}

.dropzone-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.upload-icon {
  font-size: 2rem;
}

.preview-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-top: 1rem;
}

.preview-item {
  position: relative;
  aspect-ratio: 16/9;
}

.preview-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0.375rem;
}

.remove-button {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: rgba(0, 0, 0, 0.5);
  color: white;
  border: none;
  border-radius: 50%;
  width: 1.5rem;
  height: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
}

.remove-button:hover {
  background: rgba(0, 0, 0, 0.7);
}

.button-group {
  display: flex;
  gap: 1rem;
  margin-top: 2rem;
}

.button-group .btn {
  flex: 1;
}

.hint {
  font-size: 0.875rem;
  color: #666;
  margin-left: 0.5rem;
}
</style> 