import axios from 'axios'

export default axios.create({
  baseURL: 'http://backend.test/api/v1'
})