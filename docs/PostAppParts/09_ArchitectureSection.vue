<template>
  <div class="tech-stack-section architecture">
    <h2>アーキテクチャパターン</h2>
    
    <!-- アーキテクチャパターン選択 -->
    <div class="form-group">
      <label>アーキテクチャパターン（複数選択可）</label>
      <div class="checkbox-group">
        <label>
          <input 
            type="checkbox" 
            v-model="modelValue.patterns" 
            value="mvc"
            @change="emitUpdate"
          >
          従来のMVC
        </label>
        <label>
          <input 
            type="checkbox" 
            v-model="modelValue.patterns" 
            value="modular"
            @change="emitUpdate"
          >
          モジュラーモノリス
        </label>
        <label>
          <input 
            type="checkbox" 
            v-model="modelValue.patterns" 
            value="ddd"
            @change="emitUpdate"
          >
          ドメイン駆動設計（DDD）
        </label>
        <label>
          <input 
            type="checkbox" 
            v-model="modelValue.patterns" 
            value="clean"
            @change="emitUpdate"
          >
          クリーンアーキテクチャ
        </label>
        <label>
          <input 
            type="checkbox" 
            v-model="modelValue.patterns" 
            value="onion"
            @change="emitUpdate"
          >
          オニオンアーキテクチャ
        </label>
        <label>
          <input 
            type="checkbox" 
            v-model="modelValue.patterns" 
            value="other"
            @change="emitUpdate"
          >
          その他
        </label>
      </div>
      
      <!-- その他のアーキテクチャパターン入力欄 -->
      <input 
        v-if="modelValue.patterns.includes('other')"
        v-model="modelValue.patterns_other"
        type="text"
        placeholder="その他のアーキテクチャパターンを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 選定理由 -->
    <div class="form-group">
      <label for="architecture-reason">選定理由・工夫した点</label>
      <textarea 
        id="architecture-reason"
        v-model="modelValue.reason"
        placeholder="なぜこのアーキテクチャパターンを選んだ？どんな工夫をした？"
        class="tech-stack-reason"
        @input="emitUpdate"
      ></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: {
    patterns: string[]
    patterns_other?: string
    reason: string
  }
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: () => ({
    patterns: [],
    patterns_other: '',
    reason: ''
  })
})

const emit = defineEmits(['update:modelValue'])

const emitUpdate = () => {
  emit('update:modelValue', props.modelValue)
}
</script>

<style scoped>
.tech-stack-section {
  margin-bottom: 2rem;
  padding: 1.5rem;
  border-radius: 0.5rem;
  background-color: #ffffff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.architecture {
  background-color: #ecfdf5;  /* 薄い緑色 */
  border-left: 4px solid #10b981;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
  color: #374151;
}

.checkbox-group {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 0.5rem;
  margin-bottom: 1rem;
}

.checkbox-group label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: normal;
  cursor: pointer;
}

.other-input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  margin-top: 0.5rem;
  transition: all 0.2s;
}

.other-input:focus {
  outline: none;
  border-color: #10b981;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

textarea.tech-stack-reason {
  width: 100%;
  min-height: 300px;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  resize: vertical;
  line-height: 1.8;
  transition: all 0.2s;
}

textarea.tech-stack-reason:focus {
  outline: none;
  border-color: #10b981;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .checkbox-group {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }
}
</style> 