<template>
  <div class="tech-stack-section others">
    <h2>その他</h2>
    
    <!-- 認証 -->
    <div class="form-group">
      <label>認証</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.auth" value="JWT" @change="emitUpdate"> JWT</label>
        <label><input type="checkbox" v-model="modelValue.auth" value="OAuth" @change="emitUpdate"> OAuth</label>
        <label><input type="checkbox" v-model="modelValue.auth" value="Firebase Auth" @change="emitUpdate"> Firebase Auth</label>
        <label><input type="checkbox" v-model="modelValue.auth" value="Auth0" @change="emitUpdate"> Auth0</label>
        <label><input type="checkbox" v-model="modelValue.auth" value="Supabase Auth" @change="emitUpdate"> Supabase Auth</label>
        <label><input type="checkbox" v-model="modelValue.auth" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.auth.includes('その他')"
        v-model="modelValue.auth_other"
        type="text"
        placeholder="その他の認証方式を入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- その他のツール・サービス -->
    <div class="form-group">
      <label>その他のツール・サービス</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.tools" value="CI/CD" @change="emitUpdate"> CI/CD</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="監視ツール" @change="emitUpdate"> 監視ツール</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.tools.includes('その他')"
        v-model="modelValue.tools_other"
        type="text"
        placeholder="その他のツール・サービスを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 選定理由 -->
    <div class="form-group">
      <label for="others-reason">選定理由・工夫した点</label>
      <textarea 
        id="others-reason"
        v-model="modelValue.reason"
        placeholder="なぜこれらのツール・サービスを選んだ？どんな工夫をした？"
        class="tech-stack-reason"
        @input="emitUpdate"
      ></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: {
    auth: string[]
    auth_other?: string
    tools: string[]
    tools_other?: string
    reason: string
  }
}

const props = defineProps<Props>()
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

.others {
  background-color: #faf5ff;  /* 薄い紫色 */
  border-left: 4px solid #a855f7;
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
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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
  border-color: #6366f1;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .checkbox-group {
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
  }
}
</style>
