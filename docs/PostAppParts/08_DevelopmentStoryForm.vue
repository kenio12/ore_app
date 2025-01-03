<template>
  <div class="tech-stack-section development-story">
    <h2>開発ストーリー</h2>

    <!-- 開発動機 -->
    <div class="form-group">
      <label for="motivation">開発動機（任意）</label>
      <textarea 
        id="motivation"
        v-model="modelValue.motivation"
        placeholder="なぜこのアプリを作ろうと思った？どんな課題を解決したかった？"
        class="tech-stack-reason"
      ></textarea>
    </div>

    <!-- 苦労した点・課題 -->
    <div class="form-group">
      <label for="challenges">苦労した点・課題（任意）</label>
      <textarea 
        id="challenges"
        v-model="modelValue.challenges"
        placeholder="開発中に直面した課題は？どのように解決した？"
        class="tech-stack-reason"
      ></textarea>
    </div>

    <!-- 工夫した点 -->
    <div class="form-group">
      <label for="devised_points">工夫した点（任意）</label>
      <textarea 
        id="devised_points"
        v-model="modelValue.devised_points"
        placeholder="アプリ開発でどんな工夫をした？特にこだわったポイントは？"
        class="tech-stack-reason"
      ></textarea>
    </div>

    <!-- 学んだこと -->
    <div class="form-group">
      <label for="learnings">学んだこと（任意）</label>
      <textarea 
        id="learnings"
        v-model="modelValue.learnings"
        placeholder="この開発を通じて何を学んだ？次に活かせることは？"
        class="tech-stack-reason"
      ></textarea>
    </div>

    <!-- 今後の展望 -->
    <div class="form-group">
      <label for="future_plans">今後の展望（任意）</label>
      <textarea 
        id="future_plans"
        v-model="modelValue.future_plans"
        placeholder="今後どのような機能を追加したい？どう発展させていきたい？"
        class="tech-stack-reason"
      ></textarea>
    </div>

    <!-- 開発期間 -->
    <div class="form-group">
      <label for="development_period">開発期間（任意）</label>
      <select 
        id="development_period"
        v-model="modelValue.development_period"
        class="period-select"
      >
        <option value="">選択してください</option>
        <option value="1週間未満">1週間未満</option>
        <option value="1週間〜1ヶ月">1週間〜1ヶ月</option>
        <option value="1ヶ月〜3ヶ月">1ヶ月〜3ヶ月</option>
        <option value="3ヶ月〜6ヶ月">3ヶ月〜6ヶ月</option>
        <option value="6ヶ月〜1年">6ヶ月〜1年</option>
        <option value="1年以上">1年以上</option>
      </select>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  modelValue: {
    motivation: string
    challenges: string
    devised_points: string
    learnings: string
    future_plans: string
    development_period: string
  }
}

defineProps<Props>()
</script>

<style scoped>
.tech-stack-section {
  margin-bottom: 2rem;
  padding: 1.5rem;
  border-radius: 0.5rem;
  background-color: #ffffff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.development-story {
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
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.period-select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 0.5rem;
  background-color: white;
  color: #374151;
  transition: all 0.2s;
}

.period-select:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* レスポンシブ対応 */
@media (max-width: 768px) {
  .tech-stack-section {
    padding: 1rem;
  }
}
</style> 