import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000',  // APIのベースURL
  withCredentials: true,  // CORSでクッキーを送信するために必要
  headers: {
    'Content-Type': 'application/json',
  }
})

// リクエストインターセプターでデバッグ情報を出力
api.interceptors.request.use(
  (config) => {
    console.log('API Request:', {
      url: config.url,
      method: config.method,
      headers: config.headers,
      data: config.data
    })
    return config
  },
  (error) => {
    console.error('API Request Error:', error)
    return Promise.reject(error)
  }
)

// レスポンスインターセプターでデバッグ情報を出力
api.interceptors.response.use(
  (response) => {
    console.log('API Response:', {
      status: response.status,
      data: response.data,
      headers: response.headers
    })
    return response
  },
  (error) => {
    console.error('API Response Error:', {
      status: error.response?.status,
      data: error.response?.data,
      message: error.message
    })
    return Promise.reject(error)
  }
)

export default api 