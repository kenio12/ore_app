<template>
  <div class="tech-stack-section backend">
    <h2>バックエンド環境</h2>
    
    <!-- 言語 -->
    <div class="form-group">
      <label>言語</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.languages" value="Python" @change="emitUpdate"> Python</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="Node.js" @change="emitUpdate"> Node.js</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="Go" @change="emitUpdate"> Go</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="Java" @change="emitUpdate"> Java</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="Ruby" @change="emitUpdate"> Ruby</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="PHP" @change="emitUpdate"> PHP</label>
        <label><input type="checkbox" v-model="modelValue.languages" value="Rust" @change="emitUpdate"> Rust</label>
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
      <label>フレームワーク</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.frameworks" value="FastAPI" @change="emitUpdate"> FastAPI</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Django" @change="emitUpdate"> Django</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Flask" @change="emitUpdate"> Flask</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Express" @change="emitUpdate"> Express</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="NestJS" @change="emitUpdate"> NestJS</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Spring Boot" @change="emitUpdate"> Spring Boot</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Laravel" @change="emitUpdate"> Laravel</label>
        <label><input type="checkbox" v-model="modelValue.frameworks" value="Ruby on Rails" @change="emitUpdate"> Ruby on Rails</label>
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

    <!-- ORM/ODM -->
    <div class="form-group">
      <label>ORM/ODM</label>
      <div class="checkbox-group">
        <label><input type="checkbox" v-model="modelValue.orm" value="Prisma" @change="emitUpdate"> Prisma</label>
        <label><input type="checkbox" v-model="modelValue.orm" value="TypeORM" @change="emitUpdate"> TypeORM</label>
        <label><input type="checkbox" v-model="modelValue.orm" value="Sequelize" @change="emitUpdate"> Sequelize</label>
        <label><input type="checkbox" v-model="modelValue.orm" value="SQLAlchemy" @change="emitUpdate"> SQLAlchemy</label>
        <label><input type="checkbox" v-model="modelValue.orm" value="Mongoose" @change="emitUpdate"> Mongoose</label>
        <label><input type="checkbox" v-model="modelValue.orm" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.orm.includes('その他')"
        v-model="modelValue.orm_other"
        type="text"
        placeholder="その他のORM/ODMを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- デスティングサービス -->
    <div class="form-group">
      <label>ホスティングサービス</label>
      <div class="checkbox-group">
        <template v-if="!modelValue.hosting">
          {{ modelValue.hosting = [] }}
        </template>
        <!-- PaaS -->
        <label><input type="checkbox" v-model="modelValue.hosting" value="Render" @change="emitUpdate"> Render</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Heroku" @change="emitUpdate"> Heroku</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Railway" @change="emitUpdate"> Railway</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Fly.io" @change="emitUpdate"> Fly.io</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Deta Space" @change="emitUpdate"> Deta Space</label>
        
        <!-- クラウドプロバイダー -->
        <label><input type="checkbox" v-model="modelValue.hosting" value="AWS EC2" @change="emitUpdate"> AWS EC2</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="AWS Lambda" @change="emitUpdate"> AWS Lambda</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="AWS Elastic Beanstalk" @change="emitUpdate"> AWS Elastic Beanstalk</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Google App Engine" @change="emitUpdate"> Google App Engine</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Google Cloud Run" @change="emitUpdate"> Google Cloud Run</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="Azure App Service" @change="emitUpdate"> Azure App Service</label>
        <label><input type="checkbox" v-model="modelValue.hosting" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <input 
        v-if="modelValue.hosting && modelValue.hosting.includes('その他')"
        v-model="modelValue.hosting_other"
        type="text"
        placeholder="その他のホスティングサービスを入力"
        class="other-input"
        @input="emitUpdate"
      />
    </div>

    <!-- デプロイ環境・CI/CD -->
    <div class="form-group">
      <label>デプロイ環境・CI/CD</label>
      <div class="checkbox-group">
        <template v-if="!modelValue.deployment">
          {{ modelValue.deployment = [] }}
        </template>
        <!-- Git連携 -->
        <label><input type="checkbox" v-model="modelValue.deployment" value="Git Push Deploy" @change="emitUpdate"> Git Push Deploy</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="GitHub Actions" @change="emitUpdate"> GitHub Actions</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="GitLab CI" @change="emitUpdate"> GitLab CI</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="CircleCI" @change="emitUpdate"> CircleCI</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="Jenkins" @change="emitUpdate"> Jenkins</label>
        
        <!-- クラウドプロバイダーのCI/CD -->
        <label><input type="checkbox" v-model="modelValue.deployment" value="AWS CodePipeline" @change="emitUpdate"> AWS CodePipeline</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="Google Cloud Build" @change="emitUpdate"> Google Cloud Build</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="Azure Pipelines" @change="emitUpdate"> Azure Pipelines</label>
        
        <!-- PaaSのCI/CD -->
        <label><input type="checkbox" v-model="modelValue.deployment" value="Heroku CI/CD" @change="emitUpdate"> Heroku CI/CD</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="Render Auto-Deploy" @change="emitUpdate"> Render Auto-Deploy</label>
        <label><input type="checkbox" v-model="modelValue.deployment" value="その他" @change="emitUpdate"> その他</label>
      </div>
      <!-- ... その他の入力フィールド ... -->
    </div>

    <!-- 選定理由 -->
    <div class="form-group">
      <label for="backend-reason">選定理由・工夫した点</label>
      <textarea 
        id="backend-reason"
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
    orm: string[]
    orm_other?: string
    hosting?: string[]
    hosting_other?: string
    deployment?: string[]
    deployment_other?: string
    reason: string
  }
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const emitUpdate = () => {
  if (!props.modelValue.deployment) {
    props.modelValue.deployment = []
  }
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

.backend {
  background-color: #fef2f2;  /* 薄い赤色 */
  border-left: 4px solid #ef4444;
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