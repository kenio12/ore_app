<template>
  <div class="tech-stack-section frontend">
    <h2>フロントエンド環境</h2>
    
    <!-- 言語 -->
    <div class="form-group">
      <label>言語</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.languages" value="TypeScript" @change="emitUpdate"> TypeScript</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="JavaScript" @change="emitUpdate"> JavaScript</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.languages.includes('その他')"
        v-model="modelValue.languages_other"
        type="text"
        placeholder="その他の言語を入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- フレームワーク -->
    <div class="form-group">
      <label>フレームワーク・ライブラリ</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.frameworks" value="React" @change="emitUpdate"> React</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Vue.js" @change="emitUpdate"> Vue.js</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Next.js" @change="emitUpdate"> Next.js</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Nuxt" @change="emitUpdate"> Nuxt</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Angular" @change="emitUpdate"> Angular</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Svelte" @change="emitUpdate"> Svelte</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="SvelteKit" @change="emitUpdate"> SvelteKit</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Astro" @change="emitUpdate"> Astro</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.frameworks.includes('その他')"
        v-model="modelValue.frameworks_other"
        type="text"
        placeholder="その他のフレームワークを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- UIライブラリ -->
    <div class="form-group">
      <label>UIライブラリ</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.ui" value="TailwindCSS" @change="emitUpdate"> TailwindCSS</label>
        <label><input type="checkbox" v-model="modelValue.ui" value="Material-UI" @change="emitUpdate"> Material-UI</label>
        <label><input type="checkbox" v-model="modelValue.ui" value="Chakra UI" @change="emitUpdate"> Chakra UI</label>
        <label><input type="checkbox" v-model="modelValue.ui" value="Vuetify" @change="emitUpdate"> Vuetify</label>
        <label><input type="checkbox" v-model="modelValue.ui" value="Ant Design" @change="emitUpdate"> Ant Design</label>
        <label><input type="checkbox" v-model="modelValue.ui" value="Bootstrap" @change="emitUpdate"> Bootstrap</label>
        <label><input type="checkbox" v-model="modelValue.ui" value="Shadcn UI" @change="emitUpdate"> Shadcn UI</label>
        <label><input type="checkbox" v-model="modelValue.ui" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.ui.includes('その他')"
        v-model="modelValue.ui_other"
        type="text"
        placeholder="その他のUIライブラリを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 状態管理 -->
    <div class="form-group">
      <label>状態管理</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.state" value="Redux" @change="emitUpdate"> Redux</label>
        <label><input type="checkbox" v-model="modelValue.state" value="Vuex" @change="emitUpdate"> Vuex</label>
        <label><input type="checkbox" v-model="modelValue.state" value="Pinia" @change="emitUpdate"> Pinia</label>
        <label><input type="checkbox" v-model="modelValue.state" value="Recoil" @change="emitUpdate"> Recoil</label>
        <label><input type="checkbox" v-model="modelValue.state" value="Zustand" @change="emitUpdate"> Zustand</label>
        <label><input type="checkbox" v-model="modelValue.state" value="Jotai" @change="emitUpdate"> Jotai</label>
        <label><input type="checkbox" v-model="modelValue.state" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.state.includes('その他')"
        v-model="modelValue.state_other"
        type="text"
        placeholder="その他の状態管理ライブラリを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- その他のツール -->
    <div class="form-group">
      <label>その他のツール</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.tools" value="Vite" @change="emitUpdate"> Vite</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="Webpack" @change="emitUpdate"> Webpack</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="ESLint" @change="emitUpdate"> ESLint</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="Prettier" @change="emitUpdate"> Prettier</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="Storybook" @change="emitUpdate"> Storybook</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="Vitest" @change="emitUpdate"> Vitest</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="Jest" @change="emitUpdate"> Jest</label>
        <label><input type="checkbox" v-model="modelValue.tools" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.tools.includes('その他')"
        v-model="modelValue.tools_other"
        type="text"
        placeholder="その他のツールを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 選定理由 -->
    <div class="form-group">
      <label for="frontend-reason">選定理由・工夫した点</label>
      <textarea 
        id="frontend-reason"
        v-model="modelValue.reason"
        placeholder="なぜこの技術スタックを選んだ？どんな工夫をした？"
        class="tech-stack-reason"
        @input="emitUpdate"
      ></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: {
    languages: string[]
    languages_other?: string
    frameworks: string[]
    frameworks_other?: string
    ui: string[]
    ui_other?: string
    state: string[]
    state_other?: string
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

.frontend {
  background-color: #eff6ff;  /* 薄い青色 */
  border-left: 4px solid #3b82f6;
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