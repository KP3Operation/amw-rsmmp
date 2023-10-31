import axios from "axios";

const config = {};
const apiRequest = axios.create(config);

apiRequest.interceptors.response.use(
     (response) => response,
    (error)=> {
        if (error.response.status === 401) {
            window.location.href = `/auth/login`;
        }
        return Promise.reject(error);
    }
);

export default apiRequest;
