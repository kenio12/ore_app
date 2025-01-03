<template>
  <section class="basic-info-section">
    <h2>基本情報</h2>
    
    <!-- アプリ名 -->
    <div class="form-group">
      <label for="title">アプリ名 *</label>
      <input 
        id="title"
        v-model="formData.title"
        type="text"
        required
      />
    </div>

    <!-- 公開状態 -->
    <div class="form-group">
      <label for="publish_status">このサイト内の公開状態 *</label>
      <select 
        id="publish_status"
        v-model="formData.publish_status"
        required
      >
        <option value="">選択してください</option>
        <option value="published">公開する</option>
        <option value="draft">下書き（公開しない）</option>
      </select>
    </div>

    <!-- デモURL -->
    <div class="form-group">
      <label for="demo_url">アプリへのアクセス</label>
      <input 
        id="demo_url"
        v-model="formData.demo_url"
        type="url"
        placeholder="https://your-demo-site.com"
      />
    </div>

    <!-- GitHubリポジトリURL -->
    <div class="form-group">
      <label for="github_url">GitHubリポジトリURL</label>
      <input 
        id="github_url"
        v-model="formData.github_url"
        type="url"
        placeholder="https://github.com/username/repo"
      />
    </div>

    <!-- アプリの状態 -->
    <div class="form-group">
      <label for="status">アプリの状態 *</label>
      <select 
        id="status"
        v-model="formData.status"
        required
      >
        <option value="">選択してください</option>
        <option value="completed">完成</option>
        <option value="in_development">開発中</option>
      </select>
    </div>

    <!-- 開発期間（新規追加） -->
    <div class="form-group">
      <label for="development_period">開発期間 *</label>
      <div class="period-inputs">
        <input 
          id="development_period_years"
          v-model="formData.development_period_years"
          type="number"
          min="0"
          placeholder="年"
          required
        />
        <span>年</span>
        <input 
          id="development_period_months"
          v-model="formData.development_period_months"
          type="number"
          min="0"
          max="11"
          placeholder="ヶ月"
          required
        />
        <span>ヶ月</span>
      </div>
    </div>

    <!-- ジャンル -->
    <div class="form-group">
      <label>ジャンル（複数選択可） *</label>
      <div class="genres-container">
        <div 
          v-for="genre in genres" 
          :key="genre.value" 
          class="genre-checkbox"
        >
          <input
            type="checkbox"
            :id="genre.value"
            :value="genre.value"
            v-model="formData.genres"
          />
          <label :for="genre.value">{{ genre.label }}</label>
        </div>
      </div>
    </div>

    <!-- アプリの種類（ジャンルの次に移動） -->
    <div class="form-group">
      <label>アプリの種類（複数選択可） *</label>
      <div class="app-types-container">
        <div 
          v-for="type in appTypes" 
          :key="type.value" 
          class="app-type-checkbox"
        >
          <input
            type="checkbox"
            :id="type.value"
            :value="type.value"
            :checked="formData.app_types?.includes(type.value)"
            @change="toggleAppType(type.value)"
          />
          <label :for="type.value">{{ type.label }}</label>
        </div>
      </div>
    </div>

    <!-- スクリーンショット -->
    <div class="form-group">
      <label>スクリーンショット（3枚まで）</label>
      <div 
        class="screenshot-upload"
        :class="{ 'dragging': isDragging }"
        @drop="handleDrop"
        @dragover="handleDragOver"
        @dragleave="handleDragLeave"
      >
        <input 
          type="file" 
          accept="image/*" 
          @change="handleImageUpload"
          :disabled="modelValue.screenshots.length >= 3"
          multiple
        />
        <p class="upload-hint">※ 3枚まで、1枚あたり5MB以下</p>
        <p class="drag-hint">ドラッグ&ドロップでもアップロード可能です</p>
      </div>
      
      <div class="screenshot-preview" v-if="modelValue.screenshots.length > 0">
        <div v-for="(url, index) in modelValue.screenshots" :key="index" class="preview-item">
          <img :src="url" alt="スクリーンショット" />
          <button @click="removeScreenshot(index)" class="remove-btn">削除</button>
        </div>
      </div>
    </div>

    <!-- 説明 -->
    <div class="form-group">
      <label for="description">このアプリの説明 *</label>
      <textarea 
        id="description"
        v-model="formData.description"
        required
      />
    </div>
  </section>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import api from '@/utils/api'
import { GenreLabels, AppTypeLabels } from '@/types/app'

// デフォルト値を定義
const defaultValue = {
  title: '',
  description: '',
  app_types: [] as string[],
  status: '',
  publish_status: '',
  github_url: '',
  demo_url: '',
  genres: [] as string[],
  screenshots: [] as string[],
  development_period_years: 0,
  development_period_months: 0
}

const props = defineProps<{
  modelValue: typeof defaultValue
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: typeof defaultValue): void
}>()

// formDataの初期化を修正
const formData = computed({
  get: () => ({
    ...defaultValue,
    ...props.modelValue
  }),
  set: (value) => emit('update:modelValue', value)
})

// アプリタイプの定義を修正（詳細な選択肢を残す）
const appTypes = Object.entries(AppTypeLabels).map(([value, label]) => ({
  value,
  label
}))

// ステータスの選択肢
const statusOptions = [
  { value: 'completed', label: '開発完了' },
  { value: 'in_development', label: '開発中' }
]

// ドェックボックスの切り替え処理
const toggleAppType = (value: string) => {
  const currentTypes = [...(formData.value.app_types || [])]
  const index = currentTypes.indexOf(value)
  
  if (index === -1) {
    currentTypes.push(value)
  } else {
    currentTypes.splice(index, 1)
  }
  
  emit('update:modelValue', { ...formData.value, app_types: currentTypes })
}

// ドラッグ&ドロップ状態の管理
const isDragging = ref(false)

// ドロップ処理
const handleDrop = async (event: DragEvent) => {
  isDragging.value = false
  event.preventDefault()
  const files = event.dataTransfer?.files
  if (!files) return

  // 3枚制限のチェック
  if (formData.value.screenshots.length + files.length > 3) {
    alert('スクリーンショットは最大3枚までです')
    return
  }

  // 既存のアップロード処理を利用
  const input = { files } as HTMLInputElement
  handleImageUpload({ target: input } as unknown as Event)
}

// ドラッグオーバー時のイベント
const handleDragOver = (event: DragEvent) => {
  event.preventDefault()
  isDragging.value = true
}

// ドラッグリーブ時のイベント
const handleDragLeave = () => {
  isDragging.value = false
}

const handleImageUpload = async (event: Event) => {
  const files = (event.target as HTMLInputElement).files
  if (!files) return

  for (let i = 0; i < files.length; i++) {
    if (props.modelValue.screenshots.length >= 3) break

    const file = files[i]
    console.log('アップロードするファイル:', {
      name: file.name,
      type: file.type,
      size: file.size
    })

    if (file.size > 5 * 1024 * 1024) {
      alert('画像サイズは5MB以下にしてください')
      continue
    }

    // 画像形式のチェックを追加
    if (!file.type.startsWith('image/')) {
      alert('画像ファイルのみアップロード可能です')
      continue
    }

    try {
      const formData = new FormData()
      formData.append('file', file)
      
      const response = await api.post('/api/upload/screenshots', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
      
      const newScreenshots = [...props.modelValue.screenshots, response.data.url]
      emit('update:modelValue', { ...props.modelValue, screenshots: newScreenshots })
    } catch (error: any) {
      console.error('画像アップロードエラー:', error)
      if (error.response) {
        console.error('エラーレスポンス:', error.response.data)
        // エラーの詳細情報をより分かりやすく表示
        const errorDetail = error.response.data.detail
        const errorMessage = Array.isArray(errorDetail) 
          ? errorDetail.map(err => err.msg || err).join('\n')
          : errorDetail || '不明なエラー'
        alert(`アップロードエラー:\n${errorMessage}`)
      } else {
        alert('画像のアップロードに失敗しました')
      }
    }
  }
}

const removeScreenshot = (index: number) => {
  const newScreenshots = [...props.modelValue.screenshots]
  newScreenshots.splice(index, 1)
  emit('update:modelValue', { ...props.modelValue, screenshots: newScreenshots })
}

// ==================== 修正部分（ジャンル定義） ====================
const genres = Object.entries(GenreLabels).map(([value, label]) => ({
  value,
  label
}))
// ==================== 修正部分（ジャンル定義） ====================
</script>

<style scoped>
.basic-info-section {
  background: white;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

h2 {
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
  color: #1f2937;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: #4b5563;
  font-weight: 500;
}

input, textarea, select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1rem;
}

textarea {
  min-height: 400px;
  resize: vertical;
  line-height: 1.5;
  padding: 0.75rem;
}

select {
  background-color: white;
}

input:focus, textarea:focus, select:focus {
  outline: none;
  border-color: #3b82f6;
  ring: 2px #3b82f6;
}

/* ドラッグ&ドロップ時のスタイルを追加 */
.upload-button.dragover {
  border-color: #3b82f6;
  background-color: rgba(59, 130, 246, 0.1);
}

/* ジャンルとアプリタイプで共通のスタイル */
.genres-container,
.app-types-container {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  gap: 1rem;
}

.genre-checkbox,
.app-type-checkbox {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.genre-checkbox input[type="checkbox"],
.app-type-checkbox input[type="checkbox"] {
  width: auto;
}

.screenshot-preview {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 1rem;
  margin-top: 1rem;
}

.preview-item img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 4px;
}

/* アプリの種類のラベル色を変更 */
.app-type-checkbox label {
  color: #2563eb;  /* 青系の色 */
}

/* チェックされた時の色も調整 */
.app-type-checkbox input[type="checkbox"]:checked + label {
  color: #1e40af;  /* より濃い青 */
}

/* アプリの種類の見出しラベルも青系に */
.form-group:has(.app-type-checkbox) > label {
  color: #2563eb;  /* 青系の色 */
}

/* アプリの種類の選択肢ラベル */
.app-type-checkbox label {
  color: #2563eb;  /* 青系の色 */
}

/* チェックされた時の色も調整 */
.app-type-checkbox input[type="checkbox"]:checked + label {
  color: #1e40af;  /* より濃い青 */
}

/* アプリの種類の見出しラベルを紫系に */
.form-group:has(.app-type-checkbox) > label {
  color: #6366f1;  /* 紫系の色 */
}

/* アプリの種類の選択肢ラベル */
.app-type-checkbox label {
  color: #6366f1;  /* 紫系の色 */
}

/* チェックされた時の色も調整 */
.app-type-checkbox input[type="checkbox"]:checked + label {
  color: #4f46e5;  /* より濃い紫 */
}

.period-inputs {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.period-inputs input {
  width: 80px;
}

.period-inputs span {
  color: #374151;
}

.screenshot-upload {
  border: 2px dashed #d1d5db;
  border-radius: 0.5rem;
  padding: 1rem;
  text-align: center;
  transition: all 0.3s ease;
}

.screenshot-upload.dragging {
  border-color: #3b82f6;
  background-color: rgba(59, 130, 246, 0.1);
}

.drag-hint {
  color: #6b7280;
  margin-top: 0.5rem;
  font-size: 0.875rem;
}
</style> 