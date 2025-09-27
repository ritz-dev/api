<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS - School Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
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
                        <a href="/" class="text-gray-900 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="/api-tester" class="text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">API Tester</a>
                        <a href="/docs" class="text-gray-500 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">Documentation</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</span>
                    <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse" title="System Online"></div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="gradient-bg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-white mb-6">
                    School Management System
                </h1>
                <p class="text-xl text-indigo-100 mb-8 max-w-3xl mx-auto">
                    Comprehensive API-driven platform for managing students, teachers, academics, and administrative operations in educational institutions.
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="/api-tester" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                        🚀 Test APIs
                    </a>
                    <a href="/docs" class="bg-indigo-800 text-white px-8 py-3 rounded-lg font-semibold hover:bg-indigo-900 transition duration-300">
                        📚 View Docs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">System Status</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">API Status</h3>
                    <p class="text-green-600">Online</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Database</h3>
                    <p class="text-green-600">Connected</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Services</h3>
                    <p class="text-blue-600">4 Active</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Security</h3>
                    <p class="text-indigo-600">Protected</p>
                </div>
            </div>
        </div>

        <!-- Services Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mr-4">
                        👥
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">User Management</h3>
                        <p class="text-gray-500">Authentication & Authorization</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Manage users, roles, and permissions across the platform with JWT authentication.</p>
                <div class="flex space-x-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Active</span>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">REST API</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-lg flex items-center justify-center mr-4">
                        🎓
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Academic Service</h3>
                        <p class="text-gray-500">Students & Curriculum</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Handle student enrollment, grades, courses, and academic records management.</p>
                <div class="flex space-x-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Active</span>
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">Microservice</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center mr-4">
                        👨‍🏫
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Teacher Service</h3>
                        <p class="text-gray-500">Staff & Classes</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Teacher profiles, class assignments, schedules, and performance tracking.</p>
                <div class="flex space-x-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Active</span>
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">Microservice</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mr-4">
                        💰
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Finance Service</h3>
                        <p class="text-gray-500">Billing & Payments</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Fee management, payment processing, and financial reporting capabilities.</p>
                <div class="flex space-x-2">
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">Development</span>
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">Microservice</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mr-4">
                        🔧
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">API Gateway</h3>
                        <p class="text-gray-500">Unified Access Point</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Central gateway routing requests to appropriate microservices with authentication.</p>
                <div class="flex space-x-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Active</span>
                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded">Gateway</span>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-pink-100 text-pink-600 rounded-lg flex items-center justify-center mr-4">
                        📊
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">API Testing</h3>
                        <p class="text-gray-500">Interactive Testing</p>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Comprehensive API testing interface with predefined endpoints and examples.</p>
                <div class="flex space-x-2">
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">Active</span>
                    <span class="px-2 py-1 bg-pink-100 text-pink-800 text-xs rounded">Tool</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Quick Actions</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="/api-tester" class="block p-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg text-white hover:from-blue-600 hover:to-blue-700 transition duration-300">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold">Test APIs</h3>
                    </div>
                    <p class="text-blue-100">Interactive API testing with predefined endpoints and authentication.</p>
                </a>

                <a href="/docs" class="block p-6 bg-gradient-to-r from-green-500 to-green-600 rounded-lg text-white hover:from-green-600 hover:to-green-700 transition duration-300">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold">Documentation</h3>
                    </div>
                    <p class="text-green-100">Complete API documentation and system guides.</p>
                </a>

                <a href="/health" class="block p-6 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg text-white hover:from-purple-600 hover:to-purple-700 transition duration-300">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold">Health Check</h3>
                    </div>
                    <p class="text-purple-100">Monitor system health and service status.</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">SMS Platform</h3>
                    <p class="text-gray-300">Modern school management system built with microservices architecture and API-first approach.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Technologies</h3>
                    <ul class="text-gray-300 space-y-2">
                        <li>Laravel {{ Illuminate\Foundation\Application::VERSION }}</li>
                        <li>Kubernetes Deployment</li>
                        <li>MySQL Database</li>
                        <li>JWT Authentication</li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Resources</h3>
                    <ul class="text-gray-300 space-y-2">
                        <li><a href="/api-tester" class="hover:text-white">API Tester</a></li>
                        <li><a href="/docs" class="hover:text-white">Documentation</a></li>
                        <li><a href="/health" class="hover:text-white">System Health</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 School Management System. Built for educational excellence.</p>
            </div>
        </div>
    </footer>
</body>
</html>
