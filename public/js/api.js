// API Utility for ToDo Application
class TodoAPI {
    constructor() {
        this.baseURL = '/api';
        this.token = localStorage.getItem('auth_token');
        this.setupAxios();
    }

    setupAxios() {
        // Set default headers
        if (this.token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
        }
        
        // Add request interceptor
        axios.interceptors.request.use(
            (config) => {
                // Add Authorization header for all requests if token exists
                if (this.token) {
                    config.headers['Authorization'] = `Bearer ${this.token}`;
                }
                
                // Add CSRF token for non-GET requests
                if (config.method !== 'get') {
                    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (token) {
                        config.headers['X-CSRF-TOKEN'] = token;
                    }
                }
                return config;
            },
            (error) => {
                return Promise.reject(error);
            }
        );

        // Add response interceptor
        axios.interceptors.response.use(
            (response) => {
                return response;
            },
            (error) => {
                if (error.response?.status === 401) {
                    // Unauthorized - redirect to login
                    window.location.href = '/login';
                }
                return Promise.reject(error);
            }
        );
    }

    // Auth methods
    async login(email, password) {
        try {
            const response = await axios.post('/api/auth/login', {
                email,
                password
            });
            
            if (response.data.success) {
                this.token = response.data.data.token;
                localStorage.setItem('auth_token', this.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
                // Reload the page to update the UI
                window.location.reload();
            }
            
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async register(name, email, password, password_confirmation) {
        try {
            const response = await axios.post(`${this.baseURL}/auth/register`, {
                name,
                email,
                password,
                password_confirmation
            });
            
            if (response.data.success) {
                this.token = response.data.data.token;
                localStorage.setItem('auth_token', this.token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
            }
            
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async logout() {
        try {
            await axios.post('/api/auth/logout');
            this.token = null;
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
        } catch (error) {
            console.error('Logout error:', error);
        }
    }

    async getUser() {
        try {
            const response = await axios.get(`${this.baseURL}/auth/user`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Todo methods
    async getTodos(params = {}) {
        try {
            const response = await axios.get(`${this.baseURL}/todos`, { params });
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async getTodo(id) {
        try {
            const response = await axios.get(`${this.baseURL}/todos/${id}`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async createTodo(data) {
        try {
            const response = await axios.post(`${this.baseURL}/todos`, data);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async updateTodo(id, data) {
        try {
            const response = await axios.put(`${this.baseURL}/todos/${id}`, data);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async deleteTodo(id) {
        try {
            const response = await axios.delete(`${this.baseURL}/todos/${id}`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async toggleTodoComplete(id) {
        try {
            const response = await axios.patch(`${this.baseURL}/todos/${id}/toggle`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async getTodoStats() {
        try {
            const response = await axios.get(`${this.baseURL}/todos/stats`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Category methods
    async getCategories() {
        try {
            const response = await axios.get(`${this.baseURL}/categories`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Priority methods
    async getPriorities() {
        try {
            const response = await axios.get(`${this.baseURL}/priorities`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Status methods
    async getStatuses() {
        try {
            const response = await axios.get(`${this.baseURL}/statuses`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // User methods
    async getUsers() {
        try {
            const response = await axios.get(`${this.baseURL}/users`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Comment methods
    async getComments(todoId) {
        try {
            const response = await axios.get(`${this.baseURL}/todos/${todoId}/comments`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async createComment(todoId, content) {
        try {
            const response = await axios.post(`${this.baseURL}/todos/${todoId}/comments`, {
                content
            });
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async updateComment(commentId, content) {
        try {
            const response = await axios.put(`${this.baseURL}/comments/${commentId}`, {
                content
            });
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    async deleteComment(commentId) {
        try {
            const response = await axios.delete(`${this.baseURL}/comments/${commentId}`);
            return response.data;
        } catch (error) {
            throw this.handleError(error);
        }
    }

    // Utility methods
    handleError(error) {
        if (error.response) {
            // Server responded with error status
            const { status, data } = error.response;
            
            if (data.errors) {
                // Validation errors
                return {
                    type: 'validation',
                    message: 'Please check your input',
                    errors: data.errors
                };
            }
            
            return {
                type: 'server',
                message: data.message || 'Server error occurred',
                status: status
            };
        } else if (error.request) {
            // Network error
            return {
                type: 'network',
                message: 'Network error occurred'
            };
        } else {
            // Other error
            return {
                type: 'unknown',
                message: error.message || 'An error occurred'
            };
        }
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    showError(error) {
        let message = 'An error occurred';
        
        if (error.type === 'validation') {
            message = 'Please check your input: ' + Object.values(error.errors).flat().join(', ');
        } else if (error.message) {
            message = error.message;
        }
        
        this.showNotification(message, 'danger');
    }

    showSuccess(message) {
        this.showNotification(message, 'success');
    }
}

// Initialize API instance
const api = new TodoAPI();

// Export for use in other scripts
window.TodoAPI = api;
