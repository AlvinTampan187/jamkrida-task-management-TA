import { http } from "@/services/httpService"
import { getError } from "@/services/alertService"
import router from "@/router"

export const login = async (credentials) => {
    try {
        const response = await http.post("/api/login", credentials)
        const token = response.data.content.access_token
        localStorage.setItem("token", token)
        http.defaults.headers.common.Authorization = `Bearer ${token}`
        return response.data
    }
    catch (error) {
        console.error(error)
        getError(error.response.data.message)
    }
}

export const register = async (credentials) => {
    try {
        const response = await http.post("/api/register", credentials)
        // const token = response.data.token
        // localStorage.setItem("token", token)
        // http.defaults.headers.common.Authorization = `Bearer ${token}`
        return response.data
    } catch (error) {
        console.error(error)
        getError(error.response.data.message)
    }
}


export const logout = async () => {
    try {
        const response = await http.post("/api/logout")
        localStorage.removeItem("token")
        delete http.defaults.headers.common.Authorization
        return response.data
    } catch (error) {
        console.error(error)
        getError(error.response.data.message)
    }
}

export const getUser = async () => {
    try {
        const response = await http.get("/api/profile")
        return response.data
    } catch (error) {
        console.error(error)
        router.push({ name: "login" })
        getError(error.response.data.message)
        localStorage.removeItem("token")
        delete http.defaults.headers.common.Authorization
    }
}

