<template>
  <div class="tech-stack-section database">
    <h2>データベース・ストレージ周り</h2>
    
    <!-- データベース種類 -->
    <div class="form-group">
      <label>データベース種類</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.types" value="PostgreSQL" @change="emitUpdate"> PostgreSQL</label>
        <label><input type="checkbox" v-model="modelValue.types" value="MySQL" @change="emitUpdate"> MySQL</label>
        <label><input type="checkbox" v-model="modelValue.types" value="MongoDB" @change="emitUpdate"> MongoDB</label>
        <label><input type="checkbox" v-model="modelValue.types" value="Redis" @change="emitUpdate"> Redis</label>
        <label><input type="checkbox" v-model="modelValue.types" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.types.includes('その他')"
        v-model="modelValue.types_other"
        type="text"
        placeholder="その他のデータベースを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- データベースホスティング -->
    <div class="form-group">
      <label>データベースホスティング</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.hosting" value="MongoDB Atlas" @change="emitUpdate"> MongoDB Atlas</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="AWS RDS" @change="emitUpdate"> AWS RDS</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Supabase" @change="emitUpdate"> Supabase</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Firebase" @change="emitUpdate"> Firebase</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="PlanetScale" @change="emitUpdate"> PlanetScale</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.hosting.includes('その他')"
        v-model="modelValue.hosting_other"
        type="text"
        placeholder="その他のホスティングサービスを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- ストレージサービス -->
    <div class="form-group">
      <label>ストレージサービス</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.storage" value="AWS S3" @change="emitUpdate"> AWS S3</label>
        <label><input type="checkbox" v-model="modelValue.storage" value="Cloudinary" @change="emitUpdate"> Cloudinary</label>
        <label><input type="checkbox" v-model="modelValue.storage" value="Firebase Storage" @change="emitUpdate"> Firebase Storage</label>
        <label><input type="checkbox" v-model="modelValue.storage" value="Supabase Storage" @change="emitUpdate"> Supabase Storage</label>
        <label><input type="checkbox" v-model="modelValue.storage" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.storage.includes('その他')"
        v-model="modelValue.storage_other"
        type="text"
        placeholder="その他のストレージサービスを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- 選定理由 -->
    <div class="form-group">
      <label for="database-reason">選定理由・工夫した点</label>
      <textarea 
        id="database-reason"
        v-model="modelValue.reason"
        placeholder="なぜこれらのデータベース・ストレージを選んだ？どんな工夫をした？"
        class="tech-stack-reason"
        @input="emitUpdate"
      ></textarea>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: {
    types: string[]
    types_other?: string
    hosting: string[]
    hosting_other?: string
    storage: string[]
    storage_other?: string
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

.database {
  background-color: #fff7ed;  /* 薄いオレンジ色 */
  border-left: 4px solid #f97316;
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
