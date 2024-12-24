import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/utils/api'

export const useAuthStore = defineStore('auth', () => {
  // ローカルストレージからトークンとユーザー情報を復元
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<any | null>(JSON.parse(localStorage.getItem('user') || 'null'))

  const isAuthenticated = computed(() => {
    return !!token.value && !!user.value
  })

  const login = async (email: string, password: string) => {
    try {
      const formData = new URLSearchParams()
      formData.append('username', email)
      formData.append('password', password)

      const { data } = await api.post('/api/auth/login', formData.toString(), {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      })
      
      // デバッグログを追加
      console.log('Login response:', data)
      
      token.value = data.access_token
      user.value = data.user
      
      // ローカルストレージに保存
      localStorage.setItem('token', data.access_token)
      localStorage.setItem('user', JSON.stringify(data.user))
      
      // デバッグログを追加
      console.log('After login:', { 
        token: token.value, 
        user: user.value,
        isAuthenticated: isAuthenticated.value 
      })
      
      api.defaults.headers.common['Authorization'] = `Bearer ${data.access_token}`
      
      return data
    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  }

  const logout = () => {
    token.value = null
    user.value = null
    delete api.defaults.headers.common['Authorization']
  }

  return {
    token,
    user,
    isAuthenticated,
    login,
    logout
  }
}) 