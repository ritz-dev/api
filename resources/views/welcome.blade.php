<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SMS API Tester</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        
        <style>
            pre {
                white-space: pre-wrap;
                word-wrap: break-word;
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="container mx-auto p-6">
            <div class="mb-6 text-center">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">SMS API Tester</h1>
                <p class="text-gray-600">Interactive API testing interface for the School Management System</p>
                <div class="mt-4 flex justify-center space-x-4">
                    <a href="/docs" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded" target="_blank">📚 Documentation</a>
                    <span class="text-sm text-gray-500">Version: {{ Illuminate\Foundation\Application::VERSION }}</span>
                </div>
            </div>

            <!-- Authentication Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">🔐 Authentication</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" id="auth-email" value="superadmin@example.com" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Password</label>
                        <input type="password" id="auth-password" value="superpassword" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="col-span-2">
                        <button onclick="login()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mr-2">Login</button>
                        <button onclick="clearToken()" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">Clear Token</button>
                        <div class="mt-2">
                            <span class="text-sm font-medium">Token Status:</span>
                            <span id="token-status" class="text-red-500">No token</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- API Testing Interface -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">🚀 API Endpoints</h2>
                
                <!-- Endpoint Categories -->
                <div class="mb-6">
                    <div class="flex flex-wrap gap-2 mb-4">
                        <button onclick="showCategory('health')" class="category-btn bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Health Checks</button>
                        <button onclick="showCategory('auth')" class="category-btn bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Authentication</button>
                        <button onclick="showCategory('users')" class="category-btn bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm">User Management</button>
                        <button onclick="showCategory('gateway')" class="category-btn bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded text-sm">Gateway Services</button>
                    </div>
                </div>

                <!-- Health Check Endpoints -->
                <div id="health-category" class="category-section">
                    <h3 class="text-lg font-semibold mb-3">Health Check Endpoints</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <button onclick="testEndpoint('GET', '/health')" class="endpoint-btn bg-blue-100 hover:bg-blue-200 p-3 rounded border text-left">
                            <div class="font-medium">GET /health</div>
                            <div class="text-sm text-gray-600">Main API health check</div>
                        </button>
                        <button onclick="testEndpoint('GET', '/ping')" class="endpoint-btn bg-blue-100 hover:bg-blue-200 p-3 rounded border text-left">
                            <div class="font-medium">GET /ping</div>
                            <div class="text-sm text-gray-600">Simple ping endpoint</div>
                        </button>
                        <button onclick="testEndpoint('GET', '/test-academic-health')" class="endpoint-btn bg-blue-100 hover:bg-blue-200 p-3 rounded border text-left">
                            <div class="font-medium">GET /test-academic-health</div>
                            <div class="text-sm text-gray-600">Academic service health</div>
                        </button>
                        <button onclick="testEndpoint('GET', '/test-teacher-health')" class="endpoint-btn bg-blue-100 hover:bg-blue-200 p-3 rounded border text-left">
                            <div class="font-medium">GET /test-teacher-health</div>
                            <div class="text-sm text-gray-600">Teacher service health</div>
                        </button>
                    </div>
                </div>

                <!-- Authentication Endpoints -->
                <div id="auth-category" class="category-section hidden">
                    <h3 class="text-lg font-semibold mb-3">Authentication Endpoints</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <button onclick="testEndpoint('POST', '/login', {email: document.getElementById('auth-email').value, password: document.getElementById('auth-password').value})" class="endpoint-btn bg-green-100 hover:bg-green-200 p-3 rounded border text-left">
                            <div class="font-medium">POST /login</div>
                            <div class="text-sm text-gray-600">User authentication</div>
                        </button>
                        <button onclick="testEndpoint('POST', '/logout', {}, true)" class="endpoint-btn bg-red-100 hover:bg-red-200 p-3 rounded border text-left">
                            <div class="font-medium">POST /logout</div>
                            <div class="text-sm text-gray-600">User logout (requires auth)</div>
                        </button>
                        <button onclick="testEndpoint('POST', '/me', {}, true)" class="endpoint-btn bg-yellow-100 hover:bg-yellow-200 p-3 rounded border text-left">
                            <div class="font-medium">POST /me</div>
                            <div class="text-sm text-gray-600">Get user info (requires auth)</div>
                        </button>
                        <button onclick="testEndpoint('POST', '/register', {name: 'Test User', email: 'test@example.com', password: 'password123', password_confirmation: 'password123'})" class="endpoint-btn bg-indigo-100 hover:bg-indigo-200 p-3 rounded border text-left">
                            <div class="font-medium">POST /register</div>
                            <div class="text-sm text-gray-600">User registration</div>
                        </button>
                    </div>
                </div>

                <!-- User Management Endpoints -->
                <div id="users-category" class="category-section hidden">
                    <h3 class="text-lg font-semibold mb-3">User Management (Protected)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <button onclick="testEndpoint('POST', '/users', {}, true)" class="endpoint-btn bg-purple-100 hover:bg-purple-200 p-3 rounded border text-left">
                            <div class="font-medium">POST /users</div>
                            <div class="text-sm text-gray-600">List all users</div>
                        </button>
                        <button onclick="testEndpoint('POST', '/roles', {}, true)" class="endpoint-btn bg-purple-100 hover:bg-purple-200 p-3 rounded border text-left">
                            <div class="font-medium">POST /roles</div>
                            <div class="text-sm text-gray-600">List all roles</div>
                        </button>
                        <button onclick="testEndpoint('POST', '/permissions', {}, true)" class="endpoint-btn bg-purple-100 hover:bg-purple-200 p-3 rounded border text-left">
                            <div class="font-medium">POST /permissions</div>
                            <div class="text-sm text-gray-600">List all permissions</div>
                        </button>
                    </div>
                </div>

                <!-- Gateway Services -->
                <div id="gateway-category" class="category-section hidden">
                    <h3 class="text-lg font-semibold mb-3">Gateway Services (Protected)</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <button onclick="testEndpoint('GET', '/user/health', {}, true)" class="endpoint-btn bg-orange-100 hover:bg-orange-200 p-3 rounded border text-left">
                            <div class="font-medium">GET /user/health</div>
                            <div class="text-sm text-gray-600">User service via gateway</div>
                        </button>
                        <button onclick="testEndpoint('GET', '/academic/health', {}, true)" class="endpoint-btn bg-orange-100 hover:bg-orange-200 p-3 rounded border text-left">
                            <div class="font-medium">GET /academic/health</div>
                            <div class="text-sm text-gray-600">Academic service via gateway</div>
                        </button>
                        <button onclick="testEndpoint('GET', '/teacher/health', {}, true)" class="endpoint-btn bg-orange-100 hover:bg-orange-200 p-3 rounded border text-left">
                            <div class="font-medium">GET /teacher/health</div>
                            <div class="text-sm text-gray-600">Teacher service via gateway</div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Custom Request Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">🛠️ Custom Request</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Method</label>
                        <select id="custom-method" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                            <option>GET</option>
                            <option>POST</option>
                            <option>PUT</option>
                            <option>DELETE</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-2">Endpoint</label>
                        <input type="text" id="custom-endpoint" placeholder="/api/endpoint" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium mb-2">Request Body (JSON)</label>
                        <textarea id="custom-body" placeholder='{"key": "value"}' class="w-full px-3 py-2 border border-gray-300 rounded h-20 focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="md:col-span-3">
                        <label class="flex items-center">
                            <input type="checkbox" id="custom-auth" class="mr-2">
                            <span class="text-sm">Include Authentication Token</span>
                        </label>
                    </div>
                    <div>
                        <button onclick="testCustomEndpoint()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">Send Request</button>
                    </div>
                </div>
            </div>

            <!-- Response Section -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-4">📥 Response</h2>
                <div id="loading" class="hidden text-blue-600 mb-2">
                    <div class="flex items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600 mr-2"></div>
                        Sending request...
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium">Status:</span>
                    <span id="response-status" class="text-gray-500">No request sent</span>
                </div>
                <div class="mb-2">
                    <span class="text-sm font-medium">URL:</span>
                    <span id="response-url" class="text-gray-500 break-all">-</span>
                </div>
                <pre id="response-body" class="bg-gray-50 p-4 rounded overflow-auto max-h-96 text-sm border">No response yet</pre>
            </div>
        </div>

        <script>
            let authToken = localStorage.getItem('api_token');
            updateTokenStatus();

            function updateTokenStatus() {
                const status = document.getElementById('token-status');
                if (authToken) {
                    status.textContent = 'Token available';
                    status.className = 'text-green-500';
                } else {
                    status.textContent = 'No token';
                    status.className = 'text-red-500';
                }
            }

            function showCategory(category) {
                // Hide all categories
                document.querySelectorAll('.category-section').forEach(section => {
                    section.classList.add('hidden');
                });
                
                // Show selected category
                document.getElementById(category + '-category').classList.remove('hidden');
                
                // Update button states
                document.querySelectorAll('.category-btn').forEach(btn => {
                    btn.classList.remove('bg-gray-500');
                });
            }

            async function login() {
                const email = document.getElementById('auth-email').value;
                const password = document.getElementById('auth-password').value;
                
                try {
                    await testEndpoint('POST', '/login', {email, password}, false, true);
                } catch (error) {
                    console.error('Login failed:', error);
                }
            }

            function clearToken() {
                authToken = null;
                localStorage.removeItem('api_token');
                updateTokenStatus();
                showResponse('Token cleared', 200, 'local');
            }

            async function testEndpoint(method, endpoint, body = {}, requireAuth = false, isLogin = false) {
                showLoading(true);
                
                const url = window.location.origin + endpoint;
                const headers = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };
                
                if (requireAuth && authToken) {
                    headers['Authorization'] = `Bearer ${authToken}`;
                }

                const config = {
                    method: method,
                    headers: headers
                };

                if (method !== 'GET' && Object.keys(body).length > 0) {
                    config.body = JSON.stringify(body);
                }

                try {
                    const response = await fetch(url, config);
                    const responseText = await response.text();
                    let responseData;
                    
                    try {
                        responseData = JSON.parse(responseText);
                    } catch {
                        responseData = responseText;
                    }

                    // Handle login response
                    if (isLogin && response.ok && responseData.token) {
                        authToken = responseData.token;
                        localStorage.setItem('api_token', authToken);
                        updateTokenStatus();
                    }

                    showResponse(responseData, response.status, url);
                } catch (error) {
                    showResponse({error: error.message}, 0, url);
                } finally {
                    showLoading(false);
                }
            }

            async function testCustomEndpoint() {
                const method = document.getElementById('custom-method').value;
                const endpoint = document.getElementById('custom-endpoint').value;
                const bodyText = document.getElementById('custom-body').value;
                const useAuth = document.getElementById('custom-auth').checked;
                
                let body = {};
                if (bodyText.trim()) {
                    try {
                        body = JSON.parse(bodyText);
                    } catch (error) {
                        showResponse({error: 'Invalid JSON in request body'}, 0, '');
                        return;
                    }
                }
                
                await testEndpoint(method, endpoint, body, useAuth);
            }

            function showLoading(show) {
                document.getElementById('loading').classList.toggle('hidden', !show);
            }

            function showResponse(data, status, url) {
                document.getElementById('response-status').textContent = status;
                document.getElementById('response-status').className = status >= 200 && status < 300 ? 'text-green-500' : 'text-red-500';
                document.getElementById('response-url').textContent = url;
                document.getElementById('response-body').textContent = typeof data === 'string' ? data : JSON.stringify(data, null, 2);
            }

            // Show health category by default
            showCategory('health');
        </script>
    </body>
</html>
