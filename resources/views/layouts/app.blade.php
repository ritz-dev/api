<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'SMS API Service')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Additional Head Content -->
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <!-- Modern Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-b border-gray-200/20 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo Section -->
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="h-12 w-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg hover:shadow-xl transition-shadow duration-300">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <span class="text-2xl font-black text-slate-900">SMS API</span>
                            <div class="text-xs text-slate-500 font-medium">Enterprise Platform</div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="{{ route('home') }}" class="group px-4 py-2 rounded-xl text-slate-700 hover:text-blue-600 hover:bg-blue-50/50 transition-all duration-300 font-medium">
                        <span class="flex items-center">
                            🏠 Home
                        </span>
                    </a>
                    <a href="#api-map" class="group px-4 py-2 rounded-xl text-slate-700 hover:text-purple-600 hover:bg-purple-50/50 transition-all duration-300 font-medium">
                        <span class="flex items-center">
                            🗺️ API Map
                        </span>
                    </a>
                    <a href="#documentation" class="group px-4 py-2 rounded-xl text-slate-700 hover:text-green-600 hover:bg-green-50/50 transition-all duration-300 font-medium">
                        <span class="flex items-center">
                            📚 Docs
                        </span>
                    </a>
                    <a href="/health" class="group px-4 py-2 rounded-xl text-slate-700 hover:text-orange-600 hover:bg-orange-50/50 transition-all duration-300 font-medium">
                        <span class="flex items-center">
                            ⚡ Status
                        </span>
                    </a>
                </div>
                
                <!-- CTA Button -->
                <div class="flex items-center space-x-4">
                    <a href="/docs" class="hidden sm:inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-2xl font-bold text-sm transition-all duration-300 transform hover:scale-105 hover:shadow-lg shadow-md">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Get Started
                    </a>
                    
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100/50 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Navigation Spacer -->
    <div class="h-20"></div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center">
                        <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <span class="ml-3 text-xl font-semibold">SMS API Service</span>
                    </div>
                    <p class="mt-4 text-gray-300 max-w-md">
                        Reliable and scalable SMS API service for your applications. Send messages, manage contacts, and track delivery with ease.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Resources</h3>
                    <ul class="mt-4 space-y-2">
                        <li><a href="/docs" class="text-gray-300 hover:text-white transition-colors">API Documentation</a></li>
                        <li><a href="/health" class="text-gray-300 hover:text-white transition-colors">Service Health</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Support</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase">Status</h3>
                    <ul class="mt-4 space-y-2">
                        <li class="flex items-center">
                            <div class="h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                            <span class="text-gray-300">Service Online</span>
                        </li>
                        <li class="text-gray-300 text-sm">Version: 1.0.0</li>
                        <li class="text-gray-300 text-sm">Environment: {{ app()->environment() }}</li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8">
                <p class="text-center text-gray-400 text-sm">
                    © {{ date('Y') }} SMS API Service. Built with Laravel and Tailwind CSS.
                </p>
            </div>
        </div>
    </footer>

    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>