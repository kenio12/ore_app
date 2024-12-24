<template>
  <div class="verify-email">
    <h2>メールアドレスを確認中...</h2>
    <p>しばらくお待ちください。</p>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import api from '@/utils/api'

const route = useRoute()
const router = useRouter()

onMounted(async () => {
  try {
    const token = route.query.token as string
    if (!token) {
      throw new Error('Token not found')
    }

    // /api/auth に修正
    const response = await api.get(`/api/auth/verify-email/${token}`)
    
    // 成功したらログインページへ
    alert('メールアドレスの確認が完了しました！ログインしてください。')
    router.push('/login')
    
  } catch (error) {
    console.error('Email verification error:', error)
    alert('メールアドレスの確認に失敗しました。')
    router.push('/login')
  }
})
</script>

<style scoped>
.verify-email {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100vh;
}

.verify-email h2 {
  margin-bottom: 16px;
}

.verify-email p {
  color: #666;
}
</style> 