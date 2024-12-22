import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<any | null>(null)
  const isAuthenticated = ref(false)

  const updateUser = (userData: any) => {
    user.value = userData
  }

  const updateAuthenticated = (value: boolean) => {
    isAuthenticated.value = value
  }

  const login = async (credentials: { email: string; password: string }) => {
    try {
      const formData = new FormData()
      formData.append('username', credentials.email)
      formData.append('password', credentials.password)

      const response = await fetch('http://localhost:8000/api/auth/login', {
        method: 'POST',
        body: formData
      })

      if (!response.ok) {
        throw new Error('ログインに失敗しました')
      }

      const data = await response.json()
      token.value = data.access_token
      localStorage.setItem('token', data.access_token)
      updateAuthenticated(true)
      
      await fetchUser()

      return data
    } catch (error) {
      console.error('Login error:', error)
      throw error
    }
  }

  const logout = async () => {
    token.value = null
    user.value = null
    updateAuthenticated(false)
    localStorage.removeItem('token')
  }

  const fetchUser = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/users/me', {
        headers: {
          'Authorization': `Bearer ${token.value}`
        }
      })

      if (!response.ok) {
        throw new Error('ユーザー情報の取得に失敗しました')
      }

      const userData = await response.json()
      updateUser(userData)
      updateAuthenticated(true)
    } catch (error) {
      console.error('Error fetching user:', error)
      throw error
    }
  }

  if (token.value) {
    fetchUser()
  }

  return {
    token,
    user,
    isAuthenticated,
    login,
    logout,
    fetchUser,
    updateUser,
    updateAuthenticated
  }
}) 