<template>
  <div class="environment-subsection hardware">
    <h2>ハードウェア環境</h2>

    <!-- 開発体制 -->
    <div class="form-group">
      <label>開発体制</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.team_structure" value="個人開発" @change="emitUpdate"> 個人開発</label>
        <label><input type="checkbox" v-model="modelValue.team_structure" value="少人数チーム（2-5人）" @change="emitUpdate"> 少人数チーム（2-5人）</label>
        <label><input type="checkbox" v-model="modelValue.team_structure" value="中規模チーム（6-15人）" @change="emitUpdate"> 中規模チーム（6-15人）</label>
        <label><input type="checkbox" v-model="modelValue.team_structure" value="大規模チーム（16人以上）" @change="emitUpdate"> 大規模チーム（16人以上）</label>
        <label><input type="checkbox" v-model="modelValue.team_structure" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.team_structure.includes('その他')"
        v-model="modelValue.team_structure_other"
        type="text"
        placeholder="その他の開発体制を入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- PC/OS -->
    <div class="form-group">
      <label>PC/OS</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.hardware" value="MacBook"> MacBook</label>
        <label><input type="checkbox" v-model="modelValue.hardware" value="iMac"> iMac</label>
        <label><input type="checkbox" v-model="modelValue.hardware" value="Mac mini"> Mac mini</label>
        <label><input type="checkbox" v-model="modelValue.hardware" value="Mac Pro"> Mac Pro</label>
        <label><input type="checkbox" v-model="modelValue.hardware" value="Windows PC"> Windows PC</label>
        <label><input type="checkbox" v-model="modelValue.hardware" value="Linux PC"> Linux PC</label>
        <label><input type="checkbox" v-model="modelValue.hardware" value="その他"> その他</label>
      </div>
      <input 
        v-if="modelValue.hardware.includes('その他')"
        v-model="modelValue.hardware_other"
        type="text"
        placeholder="その他のPC/OSを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 周辺機器 -->
    <div class="form-group">
      <label>周辺機器</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.peripherals" value="マルチディスプレイ"> マルチディスプレイ</label>
        <label><input type="checkbox" v-model="modelValue.peripherals" value="メカニカルキーボード"> メカニカルキーボード</label>
        <label><input type="checkbox" v-model="modelValue.peripherals" value="トラックパッド"> トラックパッド</label>
        <label><input type="checkbox" v-model="modelValue.peripherals" value="外付けマウス"> 外付けマウス</label>
        <label><input type="checkbox" v-model="modelValue.peripherals" value="その他"> その他</label>
      </div>
      <input 
        v-if="modelValue.peripherals.includes('その他')"
        v-model="modelValue.peripherals_other"
        type="text"
        placeholder="その他の周辺機器を入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 選定理由 -->
    <div class="form-group">
      <label for="hardware-reason">選定理由・工夫した点</label>
      <textarea 
        id="hardware-reason"
        v-model="modelValue.hardware_reason"
        placeholder="なぜこのハードウェア環境を選んだ？どんな工夫をした？"
        class="tech-stack-reason"
        @input="emitUpdate"
      ></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: {
    team_structure: string[]
    team_structure_other?: string
    hardware: string[]
    hardware_other?: string
    peripherals: string[]
    peripherals_other?: string
    hardware_reason: string
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

.hardware { 
  background-color: #f0fdf4;
  border-left: 4px solid #22c55e;
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