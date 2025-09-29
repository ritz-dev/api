<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Tester - SMS System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-button.active { @apply bg-blue-500 text-white; }
        .endpoint-card { transition: all 0.3s ease; }
        .endpoint-card:hover { transform: translateY(-2px); }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">SMS</h1>
                    </div>
                    <div class="ml-6 flex space-x-8">
                        <a href="/" class="text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="/api-tester" class="text-gray-900 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">API Tester</a>
                        <a href="/docs" class="text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Documentation</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">API Testing Interface</span>
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse" title="System Online"></div>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🚀 API Tester</h1>
            <p class="text-gray-600">Test and debug SMS System API endpoints with predefined examples and custom requests</p>
        </div>

        <!-- Predefined Endpoints Section -->
        <div class="mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">🎯 Quick Test Endpoints</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" x-data="endpointTester()">
                    <!-- Health Check -->
                    <div class="endpoint-card bg-green-50 border-2 border-green-200 rounded-lg p-4 cursor-pointer hover:shadow-lg" @click="loadEndpoint('health')">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-green-500 text-white text-xs rounded font-medium mr-2">GET</span>
                            <h3 class="font-semibold text-gray-800">/health</h3>
                        </div>
                        <p class="text-gray-600 text-sm">System health check</p>
                    </div>

                    <!-- Login -->
                    <div class="endpoint-card bg-blue-50 border-2 border-blue-200 rounded-lg p-4 cursor-pointer hover:shadow-lg" @click="loadEndpoint('login')">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded font-medium mr-2">POST</span>
                            <h3 class="font-semibold text-gray-800">/login</h3>
                        </div>
                        <p class="text-gray-600 text-sm">User authentication</p>
                    </div>

                    <!-- Users List -->
                    <div class="endpoint-card bg-purple-50 border-2 border-purple-200 rounded-lg p-4 cursor-pointer hover:shadow-lg" @click="loadEndpoint('users')">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-purple-500 text-white text-xs rounded font-medium mr-2">POST</span>
                            <h3 class="font-semibold text-gray-800">/users</h3>
                        </div>
                        <p class="text-gray-600 text-sm">List all users (Auth)</p>
                    </div>

                    <!-- Roles -->
                    <div class="endpoint-card bg-indigo-50 border-2 border-indigo-200 rounded-lg p-4 cursor-pointer hover:shadow-lg" @click="loadEndpoint('roles')">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-indigo-500 text-white text-xs rounded font-medium mr-2">POST</span>
                            <h3 class="font-semibold text-gray-800">/roles</h3>
                        </div>
                        <p class="text-gray-600 text-sm">List all roles (Auth)</p>
                    </div>

                    <!-- Academic Health -->
                    <div class="endpoint-card bg-orange-50 border-2 border-orange-200 rounded-lg p-4 cursor-pointer hover:shadow-lg" @click="loadEndpoint('academic-health')">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-orange-500 text-white text-xs rounded font-medium mr-2">GET</span>
                            <h3 class="font-semibold text-gray-800">/test-academic-health</h3>
                        </div>
                        <p class="text-gray-600 text-sm">Academic service health</p>
                    </div>

                    <!-- Teacher Health -->
                    <div class="endpoint-card bg-red-50 border-2 border-red-200 rounded-lg p-4 cursor-pointer hover:shadow-lg" @click="loadEndpoint('teacher-health')">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 bg-red-500 text-white text-xs rounded font-medium mr-2">GET</span>
                            <h3 class="font-semibold text-gray-800">/test-teacher-health</h3>
                        </div>
                        <p class="text-gray-600 text-sm">Teacher service health</p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('api.test') }}" method="POST" id="api-form">
            @csrf
            
            <!-- Main Grid Layout -->
            <div class="grid lg:grid-cols-2 gap-8">
                
                <!-- Left Column - Request Builder -->
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">Request Builder</h2>
                        
                        <!-- Method and URL -->
                        <div class="flex space-x-4 mb-6">
                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Method</label>
                                <select name="method" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="GET" {{ old('method', 'GET') == 'GET' ? 'selected' : '' }}>GET</option>
                                    <option value="POST" {{ old('method') == 'POST' ? 'selected' : '' }}>POST</option>
                                    <option value="PUT" {{ old('method') == 'PUT' ? 'selected' : '' }}>PUT</option>
                                    <option value="DELETE" {{ old('method') == 'DELETE' ? 'selected' : '' }}>DELETE</option>
                                    <option value="PATCH" {{ old('method') == 'PATCH' ? 'selected' : '' }}>PATCH</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                                <input type="url" name="url" value="{{ old('url') }}" 
                                       placeholder="https://api.example.com/endpoint" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <!-- Tabs Navigation -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button type="button" 
                                        class="tab-button py-2 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 active"
                                        onclick="switchTab(event, 'headers-tab')">
                                    Headers
                                </button>
                                <button type="button" 
                                        class="tab-button py-2 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                                        onclick="switchTab(event, 'params-tab')">
                                    Query Params
                                </button>
                                <button type="button" 
                                        class="tab-button py-2 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                                        onclick="switchTab(event, 'body-tab')">
                                    Body
                                </button>
                            </nav>
                        </div>

                        <!-- Headers Tab -->
                        <div id="headers-tab" class="tab-content active" x-data="keyValueManager('headers')">
                            <div class="space-y-3">
                                <div class="grid grid-cols-5 gap-2 text-sm font-medium text-gray-700">
                                    <div class="col-span-2">Key</div>
                                    <div class="col-span-2">Value</div>
                                    <div>Action</div>
                                </div>
                                
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="grid grid-cols-5 gap-2">
                                        <input type="text" 
                                               :name="`headers[${index}][key]`"
                                               x-model="item.key"
                                               placeholder="Header name"
                                               class="col-span-2 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <input type="text" 
                                               :name="`headers[${index}][value]`"
                                               x-model="item.value"
                                               placeholder="Header value"
                                               class="col-span-2 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <button type="button" 
                                                @click="removeItem(index)"
                                                class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                            ✕
                                        </button>
                                    </div>
                                </template>
                                
                                <button type="button" 
                                        @click="addItem()"
                                        class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                    <span>+</span>
                                    <span>Add Header</span>
                                </button>
                            </div>
                        </div>

                        <!-- Query Params Tab -->
                        <div id="params-tab" class="tab-content" x-data="keyValueManager('params')">
                            <div class="space-y-3">
                                <div class="grid grid-cols-5 gap-2 text-sm font-medium text-gray-700">
                                    <div class="col-span-2">Key</div>
                                    <div class="col-span-2">Value</div>
                                    <div>Action</div>
                                </div>
                                
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="grid grid-cols-5 gap-2">
                                        <input type="text" 
                                               :name="`params[${index}][key]`"
                                               x-model="item.key"
                                               placeholder="Parameter name"
                                               class="col-span-2 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <input type="text" 
                                               :name="`params[${index}][value]`"
                                               x-model="item.value"
                                               placeholder="Parameter value"
                                               class="col-span-2 px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <button type="button" 
                                                @click="removeItem(index)"
                                                class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                            ✕
                                        </button>
                                    </div>
                                </template>
                                
                                <button type="button" 
                                        @click="addItem()"
                                        class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors">
                                    <span>+</span>
                                    <span>Add Parameter</span>
                                </button>
                            </div>
                        </div>

                        <!-- Body Tab -->
                        <div id="body-tab" class="tab-content">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Content Type</label>
                                    <select name="content_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="application/json" {{ old('content_type', 'application/json') == 'application/json' ? 'selected' : '' }}>JSON</option>
                                        <option value="application/x-www-form-urlencoded" {{ old('content_type') == 'application/x-www-form-urlencoded' ? 'selected' : '' }}>Form URL Encoded</option>
                                        <option value="text/plain" {{ old('content_type') == 'text/plain' ? 'selected' : '' }}>Plain Text</option>
                                        <option value="application/xml" {{ old('content_type') == 'application/xml' ? 'selected' : '' }}>XML</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Request Body</label>
                                    <textarea name="body" 
                                              rows="10"
                                              placeholder='{"key": "value", "message": "Hello World"}'
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md font-mono text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('body') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-8">
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors font-medium">
                                Send Request
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Response Viewer -->
                <div class="space-y-6">
                    @if(isset($response))
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-6">Response</h2>
                        
                        <!-- Status and Timing -->
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-700">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $response['status'] >= 200 && $response['status'] < 300 ? 'bg-green-100 text-green-800' : 
                                       ($response['status'] >= 400 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $response['status'] ?? 'Unknown' }}
                                </span>
                            </div>
                            @if(isset($response['time']))
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-700">Time:</span>
                                <span class="text-sm text-gray-600">{{ $response['time'] }}ms</span>
                            </div>
                            @endif
                        </div>

                        <!-- Response Tabs -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button type="button" 
                                        class="response-tab-button py-2 px-1 border-b-2 border-blue-500 text-sm font-medium text-blue-600 active"
                                        onclick="switchResponseTab(event, 'response-body-tab')">
                                    Body
                                </button>
                                <button type="button" 
                                        class="response-tab-button py-2 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300"
                                        onclick="switchResponseTab(event, 'response-headers-tab')">
                                    Headers
                                </button>
                            </nav>
                        </div>

                        <!-- Response Body Tab -->
                        <div id="response-body-tab" class="response-tab-content active">
                            @if(isset($response['body']))
                                @php
                                    $isJson = false;
                                    $prettyBody = $response['body'];
                                    
                                    // Try to pretty print JSON
                                    if (is_string($response['body'])) {
                                        $decoded = json_decode($response['body'], true);
                                        if (json_last_error() === JSON_ERROR_NONE) {
                                            $prettyBody = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                            $isJson = true;
                                        }
                                    } elseif (is_array($response['body']) || is_object($response['body'])) {
                                        $prettyBody = json_encode($response['body'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                                        $isJson = true;
                                    }
                                @endphp
                                
                                @if($isJson)
                                    <pre class="whitespace-pre-wrap bg-gray-900 text-green-300 p-4 rounded-lg text-sm overflow-x-auto">{{ $prettyBody }}</pre>
                                @else
                                    <pre class="whitespace-pre-wrap bg-gray-100 text-gray-800 p-4 rounded-lg text-sm overflow-x-auto">{{ $prettyBody }}</pre>
                                @endif
                            @else
                                <div class="text-gray-500 italic">No response body</div>
                            @endif
                        </div>

                        <!-- Response Headers Tab -->
                        <div id="response-headers-tab" class="response-tab-content" style="display: none;">
                            @if(isset($response['headers']) && count($response['headers']) > 0)
                                <div class="space-y-2">
                                    @foreach($response['headers'] as $key => $value)
                                        <div class="flex flex-col sm:flex-row sm:items-center py-2 border-b border-gray-100">
                                            <div class="font-medium text-gray-700 sm:w-1/3 mb-1 sm:mb-0">{{ $key }}:</div>
                                            <div class="text-gray-600 sm:w-2/3 font-mono text-sm">
                                                @if(is_array($value))
                                                    {{ implode(', ', $value) }}
                                                @else
                                                    {{ $value }}
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-gray-500 italic">No response headers</div>
                            @endif
                        </div>
                    </div>
                    @else
                    <!-- Empty State -->
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">🚀</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Ready to test your API</h3>
                            <p class="text-gray-500">Fill out the request details and click "Send Request" to see the response here.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <script>
        // Main Alpine.js component for API tester
        function apiTester() {
            return {
                activeTab: 'custom',
                
                // Predefined endpoints
                endpoints: [
                    // Health Check Endpoints
                    {
                        id: 'api-health',
                        title: 'API Health Check',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/health',
                        category: 'Health',
                        description: 'Check if API service is running'
                    },
                    {
                        id: 'academic-health',
                        title: 'Academic Service Health',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/academic/health',
                        category: 'Health',
                        description: 'Check academic microservice status'
                    },
                    {
                        id: 'teacher-health',
                        title: 'Teacher Service Health',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/teacher/health',
                        category: 'Health',
                        description: 'Check teacher microservice status'
                    },
                    {
                        id: 'user-health',
                        title: 'User Service Health',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/user/health',
                        category: 'Health',
                        description: 'Check user microservice status'
                    },
                    
                    // Authentication Endpoints
                    {
                        id: 'login',
                        title: 'User Login',
                        method: 'POST',
                        url: 'http://89.117.52.160/api/login',
                        category: 'Auth',
                        description: 'Authenticate user with email and password',
                        body: {
                            email: 'test@example.com',
                            password: 'password123'
                        }
                    },
                    {
                        id: 'register',
                        title: 'User Registration',
                        method: 'POST',
                        url: 'http://89.117.52.160/api/register',
                        category: 'Auth',
                        description: 'Register new user account',
                        body: {
                            name: 'Test User',
                            email: 'newuser@example.com',
                            password: 'password123',
                            password_confirmation: 'password123'
                        }
                    },
                    
                    // User Management
                    {
                        id: 'user-profile',
                        title: 'Get User Profile',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/user/profile',
                        category: 'User',
                        description: 'Get authenticated user profile',
                        headers: {
                            'Authorization': 'Bearer YOUR_TOKEN_HERE'
                        }
                    },
                    {
                        id: 'users-list',
                        title: 'List All Users',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/users',
                        category: 'User',
                        description: 'Get paginated list of users'
                    },
                    
                    // Academic Endpoints
                    {
                        id: 'courses',
                        title: 'List Courses',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/academic/courses',
                        category: 'Academic',
                        description: 'Get all available courses'
                    },
                    {
                        id: 'create-course',
                        title: 'Create Course',
                        method: 'POST',
                        url: 'http://89.117.52.160/api/academic/courses',
                        category: 'Academic',
                        description: 'Create a new course',
                        body: {
                            name: 'Introduction to Programming',
                            code: 'CS101',
                            description: 'Basic programming concepts',
                            credits: 3
                        }
                    },
                    
                    // Teacher Endpoints
                    {
                        id: 'teachers',
                        title: 'List Teachers',
                        method: 'GET',
                        url: 'http://89.117.52.160/api/teacher/teachers',
                        category: 'Teacher',
                        description: 'Get all teachers'
                    }
                ],
                
                // Current request data
                url: '',
                method: 'GET',
                headers: [{ key: '', value: '' }],
                body: '',
                
                // Filter for predefined endpoints
                selectedCategory: 'All',
                
                get filteredEndpoints() {
                    if (this.selectedCategory === 'All') {
                        return this.endpoints;
                    }
                    return this.endpoints.filter(endpoint => endpoint.category === this.selectedCategory);
                },
                
                get categories() {
                    const cats = [...new Set(this.endpoints.map(ep => ep.category))];
                    return ['All', ...cats.sort()];
                },
                
                // Methods
                switchTab(tab) {
                    this.activeTab = tab;
                },
                
                loadPredefinedEndpoint(endpoint) {
                    this.url = endpoint.url;
                    this.method = endpoint.method;
                    
                    // Load headers
                    this.headers = [{ key: '', value: '' }];
                    if (endpoint.headers) {
                        this.headers = Object.entries(endpoint.headers).map(([key, value]) => ({ key, value }));
                        this.headers.push({ key: '', value: '' });
                    }
                    
                    // Load body
                    this.body = endpoint.body ? JSON.stringify(endpoint.body, null, 2) : '';
                    
                    // Switch to custom tab
                    this.activeTab = 'custom';
                },
                
                addHeader() {
                    this.headers.push({ key: '', value: '' });
                },
                
                removeHeader(index) {
                    if (this.headers.length > 1) {
                        this.headers.splice(index, 1);
                    }
                },
                
                getMethodColor(method) {
                    const colors = {
                        'GET': 'bg-green-100 text-green-800',
                        'POST': 'bg-blue-100 text-blue-800',
                        'PUT': 'bg-yellow-100 text-yellow-800',
                        'PATCH': 'bg-orange-100 text-orange-800',
                        'DELETE': 'bg-red-100 text-red-800'
                    };
                    return colors[method] || 'bg-gray-100 text-gray-800';
                },
                
                getCategoryColor(category) {
                    const colors = {
                        'Health': 'bg-green-50 border-green-200',
                        'Auth': 'bg-blue-50 border-blue-200',
                        'User': 'bg-purple-50 border-purple-200',
                        'Academic': 'bg-indigo-50 border-indigo-200',
                        'Teacher': 'bg-orange-50 border-orange-200'
                    };
                    return colors[category] || 'bg-gray-50 border-gray-200';
                }
            }
        }

        // Tab switching for response viewer
        function switchResponseTab(event, tabId) {
            // Remove active class from all response tab buttons
            document.querySelectorAll('.response-tab-button').forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            
            // Add active class to clicked button
            event.target.classList.add('border-blue-500', 'text-blue-600');
            event.target.classList.remove('border-transparent', 'text-gray-500');
            
            // Hide all response tab contents
            document.querySelectorAll('.response-tab-content').forEach(content => {
                content.style.display = 'none';
            });
            
            // Show selected response tab content
            document.getElementById(tabId).style.display = 'block';
        }
    </script>
</body>
</html>
</html>