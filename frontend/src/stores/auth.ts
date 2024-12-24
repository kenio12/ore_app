import { defineStore } from 'pinia'
import { ref } from 'vue'
import api from '@/utils/api'
import { useRouter } from 'vue-router'
import axios from 'axios'

export const useAuthStore = defineStore('auth', () => {
  const router = useRouter()
  const token = ref<string | null>(localStorage.getItem('token'))
  const user = ref<any | null>(null)
  const isAuthenticated = ref(false)

  const login = async (email: string, password: string) => {
    try {
      console.log('Attempting login with:', { email, password })

      const formData = new FormData()
      formData.append('username', email)
      formData.append('password', password)

      console.log('Sending form data:', {
        username: formData.get('username'),
        password: formData.get('password')
      })

      const response = await api.post('/api/auth/login', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })

      console.log('Login response:', response.data)

      if (response.data.token) {
        localStorage.setItem('token', response.data.token)
        token.value = response.data.token
        await fetchUser()
        
        const redirect = router.currentRoute.value.query.redirect as string
        router.push(redirect || '/')
      }
    } catch (error) {
      console.error('Login error:', error)
      if (axios.isAxiosError(error)) {
        console.error('Response data:', error.response?.data)
      }
      throw error
    }
  }

  const fetchUser = async () => {
    try {
      console.log('Fetching user data...')
      const response = await api.get('/api/users/me')
      console.log('User data response:', response)
      
      if (response.data) {
        updateUser(response.data)
        updateAuthenticated(true)
      }
    } catch (error) {
      console.error('Error fetching user:', error)
      updateAuthenticated(false)
      throw error
    }
  }

  const updateUser = (userData: any) => {
    user.value = userData
  }

  const updateAuthenticated = (value: boolean) => {
    isAuthenticated.value = value
  }

  if (token.value) {
    fetchUser().catch(console.error)
  }

  return {
    token,
    user,
    isAuthenticated,
    login,
    fetchUser,
    updateUser,
    updateAuthenticated
  }
}) 