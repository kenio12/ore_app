<template>
  <div class="environment-subsection software">
    <h2>ソフトウェア環境</h2>

    <!-- バージョン管理 -->
    <div class="form-group">
      <label>バージョン管理</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.vcs" value="Git" @change="emitUpdate"> Git</label>
        <label><input type="checkbox" v-model="modelValue.vcs" value="GitHub" @change="emitUpdate"> GitHub</label>
        <label><input type="checkbox" v-model="modelValue.vcs" value="GitLab" @change="emitUpdate"> GitLab</label>
        <label><input type="checkbox" v-model="modelValue.vcs" value="Bitbucket" @change="emitUpdate"> Bitbucket</label>
        <label><input type="checkbox" v-model="modelValue.vcs" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.vcs.includes('その他')"
        v-model="modelValue.vcs_other"
        type="text"
        placeholder="その他のバージョン管理ツールを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- エディタ/IDE -->
    <div class="form-group">
      <label>エディタ/IDE</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.editor" value="VSCode" @change="emitUpdate"> VSCode</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="IntelliJ IDEA" @change="emitUpdate"> IntelliJ IDEA</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="WebStorm" @change="emitUpdate"> WebStorm</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="PyCharm" @change="emitUpdate"> PyCharm</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Vim" @change="emitUpdate"> Vim</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Sublime Text" @change="emitUpdate"> Sublime Text</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Cursor" @change="emitUpdate"> Cursor</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Xcode" @change="emitUpdate"> Xcode</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Android Studio" @change="emitUpdate"> Android Studio</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Eclipse" @change="emitUpdate"> Eclipse</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Neovim" @change="emitUpdate"> Neovim</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="Emacs" @change="emitUpdate"> Emacs</label>
        <label><input type="checkbox" v-model="modelValue.editor" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.editor.includes('その他')"
        v-model="modelValue.editor_other"
        type="text"
        placeholder="その他のエディタ/IDEを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- AI/開発支援ツール -->
    <div class="form-group">
      <label>AI/開発支援ツール</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.ai_tools" value="GitHub Copilot" @change="emitUpdate"> GitHub Copilot</label>
        <label><input type="checkbox" v-model="modelValue.ai_tools" value="ChatGPT" @change="emitUpdate"> ChatGPT</label>
        <label><input type="checkbox" v-model="modelValue.ai_tools" value="Claude" @change="emitUpdate"> Claude</label>
        <label><input type="checkbox" v-model="modelValue.ai_tools" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.ai_tools.includes('その他')"
        v-model="modelValue.ai_tools_other"
        type="text"
        placeholder="その他のAI/開発支援ツールを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 開発環境/インフラ -->
    <div class="form-group">
      <label>開発環境/インフラ</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.dev_env" value="Docker" @change="emitUpdate"> Docker</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="Docker Compose" @change="emitUpdate"> Docker Compose</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="Kubernetes" @change="emitUpdate"> Kubernetes</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="Vagrant" @change="emitUpdate"> Vagrant</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="WSL" @change="emitUpdate"> WSL</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="VirtualBox" @change="emitUpdate"> VirtualBox</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="VMware" @change="emitUpdate"> VMware</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="AWS Cloud9" @change="emitUpdate"> AWS Cloud9</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="GitHub Codespaces" @change="emitUpdate"> GitHub Codespaces</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="GitPod" @change="emitUpdate"> GitPod</label>
        <label><input type="checkbox" v-model="modelValue.dev_env" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.dev_env.includes('その他')"
        v-model="modelValue.dev_env_other"
        type="text"
        placeholder="その他の開発環境/インフラツールを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 選定理由 -->
    <div class="form-group">
      <label for="software-reason">選定理由・工夫した点</label>
      <textarea 
        id="software-reason"
        v-model="modelValue.reason"
        placeholder="なぜこのソフトウェア環境を選んだ？どんな工夫をした？"
        class="tech-stack-reason"
        @input="emitUpdate"
      ></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: {
    vcs: string[]
    vcs_other?: string
    editor: string[]
    editor_other?: string
    ai_tools: string[]
    ai_tools_other?: string
    dev_env: string[]
    dev_env_other?: string
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
.environment-subsection {
  margin-bottom: 2rem;
  padding: 1.5rem;
  border-radius: 0.5rem;
  background-color: #ffffff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.software { 
  background-color: #f0f9ff;  /* 薄い青色 */
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