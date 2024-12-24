<template>
  <div class="post-app-container">
    <div class="container">
      <h1 class="title">アプリを紹介する</h1>

      <div class="form-box">
        <form @submit.prevent="handleSubmit">
          <section class="section">
            <h2>基本情報</h2>
            <div class="form-control">
              <label class="form-label">アプリの種類</label>
              <select v-model="formData.app_type" class="form-input" required>
                <option value="UNSPECIFIED">未指定</option>
                <option value="WEB_APP">Webアプリ</option>
                <option value="MOBILE_APP">モバイルアプリ</option>
                <option value="DESKTOP_APP">デスクトップアプリ</option>
                <option value="CLI_TOOL">CLIツール</option>
                <option value="GAME">ゲーム</option>
                <option value="OTHER">その他</option>
              </select>
            </div>

            <div class="form-control">
              <label class="form-label">開発状況</label>
              <select v-model="formData.status" class="form-input" required>
                <option value="IN_DEVELOPMENT">開発中 🚧</option>
                <option value="COMPLETED">完成済み ✨</option>
              </select>
            </div>

            <div class="form-control">
              <label class="form-label">アプリ名</label>
              <input v-model="formData.title" type="text" class="form-input" required />
            </div>

            <div class="form-control">
              <label class="form-label">説明</label>
              <textarea v-model="formData.description" class="form-input" rows="6" required />
            </div>

            <div class="form-control">
              <label class="form-label">デモURL</label>
              <input v-model="formData.demo_url" type="url" class="form-input" />
            </div>

            <div class="form-control">
              <label class="form-label">GitHubリンク</label>
              <input v-model="formData.github_url" type="url" class="form-input" />
            </div>

            <div class="form-control">
              <label class="form-label">アイコン</label>
              <div class="icon-selector">
                <div class="prefix-icon">
                  <label>前</label>
                  <input v-model="formData.prefix_icon" type="text" class="form-input" placeholder="🗡️" />
                </div>
                <div class="suffix-icon">
                  <label>後</label>
                  <input v-model="formData.suffix_icon" type="text" class="form-input" placeholder="🏴‍☠️" />
                </div>
              </div>
            </div>

            <div class="form-control">
              <label class="form-label">スクリーンショット（1-3枚）</label>
              <div 
                class="dropzone"
                :class="{ 'dropzone-active': isDragging }"
                @dragenter.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @dragover.prevent
                @drop.prevent="handleDrop"
              >
                <div class="dropzone-content">
                  <span v-if="!screenshots.length">
                    ここにファイルをドラッグ＆ドロップ<br>
                    または
                  </span>
                  <input 
                    type="file" 
                    @change="handleFileChange" 
                    accept="image/*" 
                    multiple 
                    class="file-input"
                    id="file-input"
                  />
                  <label for="file-input" class="file-label">
                    ファイルを選択
                  </label>
                </div>
              </div>

              <div class="preview-container" v-if="previewUrls.length">
                <div v-for="(url, index) in previewUrls" :key="index" class="preview-item">
                  <img :src="url" alt="プレビュー" />
                  <button type="button" @click="removeImage(index)" class="remove-btn">×</button>
                </div>
              </div>
            </div>
          </section>

          <section class="section">
            <h2>技術スタック 🛠️</h2>
            
            <div class="tech-stack">
              <h3>フロントエンド</h3>
              <div class="form-control">
                <label>言語</label>
                <select v-model="formData.frontend.language" class="form-input">
                  <option value="typescript">TypeScript</option>
                  <option value="javascript">JavaScript</option>
                  <option value="other">その他</option>
                </select>
                <input 
                  v-if="formData.frontend.language === 'other'"
                  v-model="formData.frontend.customLanguage"
                  type="text"
                  placeholder="言語を入力"
                  class="form-input"
                />
              </div>
              <div class="form-control">
                <label>選定理由</label>
                <textarea 
                  v-model="formData.frontend.reason"
                  class="form-input"
                  rows="3"
                  placeholder="この技術を選んだ理由を教えてください"
                />
              </div>
            </div>

            <div class="tech-stack">
              <h3>バックエンド</h3>
              <div class="form-control">
                <label>言語</label>
                <select v-model="formData.backend.language" class="form-input">
                  <option value="typescript">TypeScript</option>
                  <option value="javascript">JavaScript</option>
                  <option value="other">その他</option>
                </select>
                <input 
                  v-if="formData.backend.language === 'other'"
                  v-model="formData.backend.customLanguage"
                  type="text"
                  placeholder="言語を入力"
                  class="form-input"
                />
              </div>
              <div class="form-control">
                <label>選定理由</label>
                <textarea 
                  v-model="formData.backend.reason"
                  class="form-input"
                  rows="3"
                  placeholder="この技術を選んだ理由を教えてください"
                />
              </div>
            </div>

            <div class="tech-stack">
              <h3>データベース</h3>
              <div class="form-control">
                <label>言語</label>
                <select v-model="formData.database.language" class="form-input">
                  <option value="typescript">TypeScript</option>
                  <option value="javascript">JavaScript</option>
                  <option value="other">その他</option>
                </select>
                <input 
                  v-if="formData.database.language === 'other'"
                  v-model="formData.database.customLanguage"
                  type="text"
                  placeholder="言語を入力"
                  class="form-input"
                />
              </div>
              <div class="form-control">
                <label>選定理由</label>
                <textarea 
                  v-model="formData.database.reason"
                  class="form-input"
                  rows="3"
                  placeholder="この技術を選んだ理由を教えてください"
                />
              </div>
            </div>
          </section>

          <section class="section">
            <h2>開発ストーリー 📖</h2>
            <div class="form-control">
              <label>企画のきっかけ</label>
              <textarea 
                v-model="formData.story.motivation"
                class="form-input"
                rows="4"
              />
            </div>
          </section>

          <section class="section">
            <h2>開発日記 📝</h2>
            <div class="diary-entries">
              <div 
                v-for="(entry, index) in formData.diary"
                :key="index"
                class="diary-entry"
              >
                <div class="form-control">
                  <label>日付</label>
                  <input 
                    type="date"
                    v-model="entry.date"
                    class="form-input"
                  />
                </div>
                <div class="form-control">
                  <label>進捗報告</label>
                  <textarea 
                    v-model="entry.content"
                    class="form-input"
                    rows="4"
                  />
                </div>
                <div class="form-control">
                  <label>画像（オプション）</label>
                  <input 
                    type="file"
                    @change="(e) => handleDiaryImage(e, index)"
                    accept="image/*"
                    class="form-input"
                  />
                </div>
              </div>
              <button 
                type="button"
                @click="addDiaryEntry"
                class="btn btn-secondary"
              >
                新しいエントリーを追加
              </button>
            </div>
          </section>

          <div class="button-group">
            <button type="submit" class="btn btn-primary">投稿する</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const formData = ref({
  title: '',
  description: '',
  demo_url: '',
  github_url: '',
  app_type: 'UNSPECIFIED',
  status: 'IN_DEVELOPMENT',
  prefix_icon: '🗡️',
  suffix_icon: '🏴‍☠️',
  frontend: {
    language: '',
    framework: '',
    hosting: '',
    customLanguage: '',
    customFramework: '',
    customHosting: '',
    reason: ''
  },
  backend: {
    language: '',
    customLanguage: '',
    reason: ''
  },
  database: {
    language: '',
    customLanguage: '',
    reason: ''
  },
  story: {
    motivation: '',
    challenges: '',
    current_issues: '',
    failures: '',
    future_plans: ''
  },
  diary: [
    {
      date: '',
      content: '',
      image: null as File | null
    }
  ]
})

// スクリーンショット関連の状態
const screenshots = ref<File[]>([])
const previewUrls = ref<string[]>([])

const isDragging = ref(false)

// ファイル選択時の処理
const handleFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement
  if (!input.files) return

  // 画像ファイルだけをフィルタリング
  const files = Array.from(input.files).filter(file => 
    file.type.startsWith('image/')
  )

  // 既存の画像数を考慮して追加可能な枚数を計算
  const remainingSlots = 3 - screenshots.value.length
  if (remainingSlots <= 0) {
    alert('スクリーンショットは最大3枚までです')
    return
  }

  // 追加可能な枚数だけ取得
  const newFiles = files.slice(0, remainingSlots)
  
  // 既存の配列に追加
  screenshots.value = [...screenshots.value, ...newFiles]

  // 新しい画像のプレビューURLを生成して追加
  const newPreviewUrls = newFiles.map(file => URL.createObjectURL(file))
  previewUrls.value = [...previewUrls.value, ...newPreviewUrls]

  // 入力をリセット（同じファイルを再度選択できるように）
  ;(event.target as HTMLInputElement).value = ''
}

// プレビュー画像の削除
const removeImage = (index: number) => {
  screenshots.value.splice(index, 1)
  URL.revokeObjectURL(previewUrls.value[index])
  previewUrls.value.splice(index, 1)
}

// ドラッグ&ドロップの処理
const handleDrop = (event: DragEvent) => {
  isDragging.value = false
  if (!event.dataTransfer) return

  const files = Array.from(event.dataTransfer.files).filter(file => 
    file.type.startsWith('image/')
  )

  // 既存の画像数を考慮して追加可能な枚数を計算
  const remainingSlots = 3 - screenshots.value.length
  if (remainingSlots <= 0) {
    alert('スクリーンショットは最大3枚までです')
    return
  }

  // 追加可能な枚数だけ取得
  const newFiles = files.slice(0, remainingSlots)
  
  // 既存の配列に追加
  screenshots.value = [...screenshots.value, ...newFiles]

  // 新しい画像のプレビューURLを生成して追加
  const newPreviewUrls = newFiles.map(file => URL.createObjectURL(file))
  previewUrls.value = [...previewUrls.value, ...newPreviewUrls]
}

// 開発日記エントリーを追加
const addDiaryEntry = () => {
  formData.value.diary.push({
    date: '',
    content: '',
    image: null
  })
}

// 開発日記の画像をハンドル
const handleDiaryImage = (event: Event, index: number) => {
  const input = event.target as HTMLInputElement
  if (input.files && input.files[0]) {
    formData.value.diary[index].image = input.files[0]
  }
}

const handleSubmit = async () => {
  try {
    // まず画像をアップロード
    if (screenshots.value.length > 0) {
      const imageFormData = new FormData()
      screenshots.value.forEach(file => {
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
      formData.value.screenshots = uploadedUrls
    }

    // アプリデータを送信
    const response = await fetch('http://localhost:8000/api/apps/', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      },
      body: JSON.stringify(formData.value)
    })

    if (!response.ok) {
      throw new Error('投稿に失敗しました')
    }

    alert('投稿成功！')
    router.push('/')
  } catch (error) {
    console.error('Error:', error)
    alert('投稿に失敗しました')
  }
}
</script>

<style scoped>
.preview-container {
  display: flex;
  gap: 1rem;
  margin-top: 1rem;
}

.preview-item {
  position: relative;
  width: 150px;
  height: 150px;
}

.preview-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 4px;
}

.remove-btn {
  position: absolute;
  top: -8px;
  right: -8px;
  width: 24px;
  height: 24px;
  border-radius: 50%;
  background: #ff4444;
  color: white;
  border: none;
  cursor: pointer;
}

.remove-btn:hover {
  background: #ff0000;
}

.dropzone {
  border: 2px dashed #ccc;
  border-radius: 4px;
  padding: 2rem;
  text-align: center;
  transition: all 0.3s ease;
  background: #f8f9fa;
  cursor: pointer;
}

.dropzone-active {
  border-color: #4CAF50;
  background: #E8F5E9;
}

.dropzone-content {
  color: #666;
  font-size: 1.1rem;
  line-height: 1.6;
}

.file-input {
  display: none;
}

.file-label {
  display: inline-block;
  padding: 0.5rem 1rem;
  background: #007bff;
  color: white;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 1rem;
}

.file-label:hover {
  background: #0056b3;
}

.icon-selector {
  display: flex;
  gap: 1rem;
}

.prefix-icon, .suffix-icon {
  flex: 1;
}

.prefix-icon label, .suffix-icon label {
  display: block;
  margin-bottom: 0.5rem;
}

.prefix-icon input, .suffix-icon input {
  width: 100%;
  text-align: center;
  font-size: 1.5rem;
}

.section {
  margin-bottom: 2rem;
  padding: 1.5rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.section h2 {
  margin-bottom: 1.5rem;
  color: #333;
  font-size: 1.5rem;
}

.tech-stack {
  margin-bottom: 1.5rem;
}

.diary-entry {
  padding: 1rem;
  margin-bottom: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}
</style> 