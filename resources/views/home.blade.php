@extends('layouts.app')

@section('title', 'SMS Dashboard - School Management System')

@section('content')
<!-- Dashboard Header -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">SMS Dashboard</h1>
                <p class="text-blue-100">School Management System Overview</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-blue-100">Current Time</div>
                <div class="text-lg font-semibold" id="currentTime"></div>
            </div>
        </div>
    </div>
</div>

<!-- Main Dashboard Content -->
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- System Status Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- API Status -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">API Status</h3>
                        <p class="text-sm text-green-600 font-medium" id="apiStatus">Operational</p>
                    </div>
                </div>
            </div>

            <!-- Database Status -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Database</h3>
                        <p class="text-sm text-blue-600 font-medium" id="dbStatus">Connected</p>
                    </div>
                </div>
            </div>

            <!-- Services Count -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Services</h3>
                        <p class="text-sm text-purple-600 font-medium">4 Active</p>
                    </div>
                </div>
            </div>

            <!-- API Endpoints -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Endpoints</h3>
                        <p class="text-sm text-orange-600 font-medium">50+ Available</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <!-- Students Management -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Students</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Manage student records, enrollment, and profiles</p>
                    <button onclick="testEndpoint('/gateway/user/students')" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 text-sm font-medium">
                        Test API
                    </button>
                </div>
            </div>

            <!-- Teachers Management -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Teachers</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Teacher profiles, subjects and schedules</p>
                    <button onclick="testEndpoint('/gateway/user/teachers')" class="w-full bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-colors duration-200 text-sm font-medium">
                        Test API
                    </button>
                </div>
            </div>

            <!-- Academic Management -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Academic</h3>
                    </div>
                    <p class="text-gray-600 text-sm mb-4">Classes, sections, schedules and grades</p>
                    <button onclick="testEndpoint('/gateway/academic/classes')" class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors duration-200 text-sm font-medium">
                        Test API
                    </button>
                </div>
            </div>
        </div>

        <!-- API Testing Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">API Endpoint Tester</h2>
                <p class="text-gray-600 text-sm">Test School Management System API endpoints in real-time</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Endpoint Selector -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Endpoint</label>
                        <select id="endpointSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:border-blue-500">
                            <option value="/gateway/health">GET /gateway/health - System Health</option>
                            <option value="/gateway/ping">GET /gateway/ping - API Ping</option>
                            <option value="/gateway/test-academic-health">GET /gateway/test-academic-health - Academic Service</option>
                            <option value="/gateway/test-teacher-health">GET /gateway/test-teacher-health - Teacher Service</option>
                        </select>
                        <button onclick="testSelectedEndpoint()" class="w-full mt-4 bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium">
                            🚀 Test Endpoint
                        </button>
                    </div>
                    
                    <!-- Response Display -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Response</label>
                        <div id="responseDisplay" class="bg-gray-50 border border-gray-300 rounded-lg p-4 h-40 overflow-auto font-mono text-sm">
                            <div class="text-gray-500">Select and test an endpoint to see the response...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services Overview -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Microservices Architecture</h2>
                <p class="text-gray-600 text-sm">School Management System distributed services</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- API Gateway -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">API Gateway</h3>
                        <p class="text-xs text-green-600 mt-1">✓ Online</p>
                    </div>

                    <!-- User Service -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">User Service</h3>
                        <p class="text-xs text-green-600 mt-1">✓ Online</p>
                    </div>

                    <!-- Academic Service -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">Academic Service</h3>
                        <p class="text-xs text-green-600 mt-1">✓ Online</p>
                    </div>

                    <!-- Teacher Service -->
                    <div class="text-center p-4 border border-gray-200 rounded-lg">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-900">Teacher Service</h3>
                        <p class="text-xs text-green-600 mt-1">✓ Online</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                        </svg>
                    </div>
                </div>
            </a>
            
            <!-- Live Status Card -->
            <a href="/health" class="group relative p-8 bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-xl rounded-3xl border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-blue-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">⚡ Live Status</h3>
                    <p class="text-white/60 mb-4">Real-time system monitoring & performance</p>
                    <div class="flex items-center justify-center text-green-300 font-semibold group-hover:text-green-200 transition-colors">
                        View Dashboard
                        <svg class="w-5 h-5 ml-2 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-white mb-2">10K+</div>
                <div class="text-white/60 text-sm font-medium">Active Developers</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-white mb-2">1B+</div>
                <div class="text-white/60 text-sm font-medium">Messages Sent</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-white mb-2">99.9%</div>
                <div class="text-white/60 text-sm font-medium">Uptime SLA</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-white mb-2">190+</div>
                <div class="text-white/60 text-sm font-medium">Countries</div>
            </div>
        </div>
    </div>
</div>

<!-- System Monitoring Dashboard -->
<div class="relative py-24 bg-slate-50 overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-purple-50"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-grid-slate-900/[0.04] bg-[size:80px_80px]"></div>
        <div class="absolute top-20 right-20 w-64 h-64 bg-gradient-to-br from-blue-200/40 to-purple-200/40 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 left-20 w-80 h-80 bg-gradient-to-br from-purple-200/30 to-indigo-200/30 rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500/10 to-purple-500/10 backdrop-blur-sm rounded-2xl border border-blue-200/50 shadow-lg mb-8">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse shadow-lg shadow-green-500/50"></div>
                <span class="text-slate-700 font-bold">🚀 Live System Monitoring</span>
            </div>
            
            <h2 class="text-5xl md:text-6xl font-black mb-6">
                <span class="bg-gradient-to-r from-slate-900 via-blue-800 to-purple-800 bg-clip-text text-transparent">
                    Real-time Dashboard
                </span>
            </h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                Monitor our enterprise infrastructure with <span class="font-bold text-slate-900">military-grade reliability</span> across 
                <span class="font-bold text-blue-600">190+ countries</span>
            </p>
        </div>
        
        <!-- Status Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16" id="service-status">
            <!-- API Status Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-3xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl border border-white/20 group-hover:shadow-2xl transition-all duration-500 transform group-hover:-translate-y-3">
                    <!-- Status Indicator -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-green-500 rounded-full animate-pulse shadow-lg shadow-green-500/50"></div>
                            <span class="text-green-600 font-bold text-sm">OPERATIONAL</span>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-blue-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 mb-3">API Gateway</h3>
                    <p class="text-slate-600 mb-6">Global edge servers responding in <span class="font-bold text-slate-900">23ms</span> average</p>
                    
                    <!-- Performance Bar -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Response Time</span>
                            <span class="font-bold text-green-600">Excellent</span>
                        </div>
                        <div class="h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-green-500 to-blue-500 rounded-full w-4/5 animate-pulse shadow-lg shadow-green-500/30"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Database Status Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-3xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl border border-white/20 group-hover:shadow-2xl transition-all duration-500 transform group-hover:-translate-y-3">
                    <!-- Status Indicator -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-green-500 rounded-full animate-pulse shadow-lg shadow-green-500/50"></div>
                            <span class="text-green-600 font-bold text-sm">HEALTHY</span>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 mb-3">Database Cluster</h3>
                    <p class="text-slate-600 mb-6">Multi-region replication with <span class="font-bold text-slate-900">0.01%</span> error rate</p>
                    
                    <!-- Performance Bar -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Connection Pool</span>
                            <span class="font-bold text-green-600">Optimal</span>
                        </div>
                        <div class="h-3 bg-slate-200 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-purple-500 to-pink-500 rounded-full w-5/6 animate-pulse shadow-lg shadow-purple-500/30"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Infrastructure Status Card -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-orange-500/20 to-red-500/20 rounded-3xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl p-8 shadow-xl border border-white/20 group-hover:shadow-2xl transition-all duration-500 transform group-hover:-translate-y-3">
                    <!-- Status Indicator -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-4 h-4 bg-green-500 rounded-full animate-pulse shadow-lg shadow-green-500/50"></div>
                            <span class="text-green-600 font-bold text-sm">ALL SYSTEMS GO</span>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-black text-slate-900 mb-3">Global Network</h3>
                    <p class="text-slate-600 mb-6">Enterprise uptime with <span class="font-bold text-slate-900">99.99%</span> availability</p>
                    
                    <!-- Uptime Display -->
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Current Uptime</span>
                            <span class="font-bold text-green-600">247 Days</span>
                        </div>
                        <div class="text-center">
                            <div class="text-4xl font-black bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">99.99%</div>
                            <div class="text-sm text-slate-500 mt-1">Last 365 days</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Performance Metrics -->
        <div class="bg-white/60 backdrop-blur-xl rounded-3xl p-8 shadow-xl border border-white/30">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <div class="text-3xl md:text-4xl font-black text-slate-900 mb-2">23ms</div>
                    <div class="text-slate-600 font-medium">Avg Response</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black text-slate-900 mb-2">1.2M</div>
                    <div class="text-slate-600 font-medium">Req/Min</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black text-slate-900 mb-2">0.001%</div>
                    <div class="text-slate-600 font-medium">Error Rate</div>
                </div>
                <div>
                    <div class="text-3xl md:text-4xl font-black text-slate-900 mb-2">15</div>
                    <div class="text-slate-600 font-medium">Global Regions</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="py-24 bg-white relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50/30 via-transparent to-purple-50/30"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-blue-200/20 to-transparent rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-purple-200/20 to-transparent rounded-full blur-3xl"></div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-6 py-2 bg-gradient-to-r from-blue-100 to-purple-100 text-blue-800 rounded-full text-sm font-semibold mb-6">
                ⚡ Enterprise Features
            </div>
            <h2 class="text-4xl md:text-6xl font-black text-gray-900 mb-6 bg-gradient-to-r from-gray-900 via-blue-800 to-purple-800 bg-clip-text text-transparent">
                Built for Scale
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Professional SMS infrastructure with enterprise-grade features designed for mission-critical applications
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1: Fast Delivery -->
            <div class="group relative bg-gradient-to-br from-white to-blue-50/50 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 border border-blue-100/50">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">⚡ Instant Delivery</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sub-second message delivery with global carrier partnerships and intelligent routing algorithms.
                    </p>
                    <div class="mt-6 flex items-center text-sm text-blue-600 font-semibold">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                        99.9% delivery rate
                    </div>
                </div>
            </div>
            
            <!-- Feature 2: Reliability -->
            <div class="group relative bg-gradient-to-br from-white to-green-50/50 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 border border-green-100/50">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🛡️ Ultra Reliable</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Military-grade infrastructure with redundant systems, automatic failover, and 24/7 monitoring.
                    </p>
                    <div class="mt-6 flex items-center text-sm text-green-600 font-semibold">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                        99.99% uptime SLA
                    </div>
                </div>
            </div>
            
            <!-- Feature 3: Scalability -->
            <div class="group relative bg-gradient-to-br from-white to-purple-50/50 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 border border-purple-100/50">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">📈 Infinite Scale</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Auto-scaling cloud infrastructure handles billions of messages with intelligent load distribution.
                    </p>
                    <div class="mt-6 flex items-center text-sm text-purple-600 font-semibold">
                        <span class="w-2 h-2 bg-purple-500 rounded-full mr-2"></span>
                        1B+ msgs/day capacity
                    </div>
                </div>
            </div>
            
            <!-- Feature 4: Security -->
            <div class="group relative bg-gradient-to-br from-white to-orange-50/50 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 border border-orange-100/50">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-400/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">🔐 Fort Knox Security</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Bank-level encryption, OAuth2 authentication, and full compliance with GDPR & SOC2 standards.
                    </p>
                    <div class="mt-6 flex items-center text-sm text-orange-600 font-semibold">
                        <span class="w-2 h-2 bg-orange-500 rounded-full mr-2"></span>
                        SOC2 Type II certified
                    </div>
                </div>
            </div>
            </div>
            
            <!-- Feature 5 -->
            <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg mb-6">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Analytics</h3>
                <p class="text-gray-600">
                    Detailed delivery reports, analytics dashboard, and real-time monitoring capabilities.
                </p>
            </div>
            
            <!-- Feature 6 -->
            <div class="bg-white rounded-xl p-8 shadow-sm hover:shadow-md transition-shadow">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-red-100 rounded-lg mb-6">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 110 19.5 9.75 9.75 0 010-19.5z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Global Reach</h3>
                <p class="text-gray-600">
                    Send messages to over 200 countries with local number support and carrier optimization.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- API Endpoints Preview -->
<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Quick Start</h2>
            <p class="text-lg text-gray-600">Get started with our SMS API in minutes</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-6">Send Your First SMS</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">1</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Get API Key</h4>
                            <p class="text-gray-600">Register and obtain your API authentication key</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">2</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Make API Call</h4>
                            <p class="text-gray-600">Send POST request to /api/sms/send endpoint</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold">3</div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Track Delivery</h4>
                            <p class="text-gray-600">Monitor message status and delivery reports</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <a href="/docs" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        View Full Documentation
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <div>
                <div class="bg-gray-900 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-medium text-gray-300">Example Request</h4>
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        </div>
                    </div>
                    <pre class="text-sm text-gray-300"><code>curl -X POST {{ request()->getSchemeAndHttpHost() }}/api/sms/send \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "to": "+1234567890",
    "message": "Hello from SMS API!",
    "from": "YourApp"
  }'</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
            Join thousands of developers already using our SMS API to power their applications
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/docs" class="bg-white text-blue-600 hover:bg-gray-50 px-8 py-3 rounded-lg font-semibold transition-colors">
                Get Started Free
            </a>
            <a href="/health" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-3 rounded-lg font-semibold transition-colors">
                View Service Status
            </a>
        </div>
    </div>
</div>

<!-- API Map Section -->
<div id="api-map" class="py-24 bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 text-white relative overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:40px_40px]"></div>
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500/20 to-purple-500/20 backdrop-blur-sm rounded-2xl border border-white/20 shadow-lg mb-8">
                <span class="text-white font-bold">🗺️ API Endpoints Map</span>
            </div>
            <h2 class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                Explore Our API
            </h2>
            <p class="text-xl text-white/70 max-w-3xl mx-auto leading-relaxed">
                Complete School Management System API with <span class="text-white font-semibold">RESTful endpoints</span> for students, teachers, and academic management
            </p>
        </div>
        
        <!-- API Endpoints Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Students Management -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-blue-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm font-bold">POST</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Students</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/user/students
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Manage student records, enrollments, and personal information with full CRUD operations.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Student enrollment
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                            Profile management
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Teachers Management -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm font-bold">GET</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Teachers</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/user/teachers
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Teacher profiles, subjects, schedules and performance tracking with dashboard analytics.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                            Schedule management
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                            Subject assignments
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Academic Management -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-pink-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm font-bold">GET</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Academic</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/academic/classes
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Academic year, classes, sections, subjects, schedules and student enrollment management.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                            Class sections
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-pink-400 rounded-full mr-2"></span>
                            Cost breakdown
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Attendance Management -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-400/10 to-red-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-orange-500/20 text-orange-300 rounded-full text-sm font-bold">POST</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Attendance</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/academic/attendance
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Track student attendance with daily reports, analytics and automated notifications.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-orange-400 rounded-full mr-2"></span>
                            Daily tracking
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            Automated reports
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Schedules Management -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/10 to-blue-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 rounded-full text-sm font-bold">GET</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Schedules</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/academic/weekly-schedule
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Manage weekly and daily class schedules, time slots and teacher assignments.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-cyan-400 rounded-full mr-2"></span>
                            Weekly planning
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                            Time slot management
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Student Grades -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-400/10 to-purple-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-indigo-500/20 text-indigo-300 rounded-full text-sm font-bold">GET</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Grades</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/academic/grades
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Student grade management with report cards, analytics and performance tracking.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                            Report cards
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                            Performance analytics
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- API Quick Reference -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 shadow-xl">
            <h3 class="text-3xl font-black text-white mb-8 text-center">Quick Reference</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div>
                    <h4 class="text-xl font-bold text-white mb-4">Base URL</h4>
                    <div class="bg-black/30 rounded-xl p-4 font-mono text-green-300 text-sm">
                        https://api.sms-service.com/v1
                    </div>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-white mb-4">Authentication</h4>
                    <div class="bg-black/30 rounded-xl p-4 font-mono text-blue-300 text-sm">
                        Authorization: Bearer {api_key}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- API Documentation Section -->
<div id="documentation" class="py-24 bg-white relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-green-50"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-blue-100/40 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-green-100/40 to-transparent rounded-full blur-3xl"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Header -->
        <div class="text-center mb-20">
            <div class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500/10 to-blue-500/10 backdrop-blur-sm rounded-2xl border border-green-200/50 shadow-lg mb-8">
                <span class="text-slate-700 font-bold">📚 Developer Resources</span>
            </div>
            <h2 class="text-5xl md:text-6xl font-black mb-6 bg-gradient-to-r from-slate-900 via-green-800 to-blue-800 bg-clip-text text-transparent">
                Complete Documentation
            </h2>
            <p class="text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
                Everything you need to integrate our SMS API with <span class="font-bold text-slate-900">code examples</span>, 
                <span class="font-bold text-slate-900">SDKs</span>, and comprehensive guides
            </p>
        </div>
        
        <!-- Documentation Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Quick Start Guide -->
            <div class="group relative bg-gradient-to-br from-white to-green-50/50 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-green-100/50">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">🚀 Quick Start</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Get up and running in minutes with our comprehensive School Management System API integration guide.
                    </p>
                    <div class="bg-slate-900 rounded-xl p-4 mb-6">
                        <code class="text-green-400 text-sm font-mono">
                            curl -X POST /api/user/students<br/>
                            -H "Authorization: Bearer {token}"<br/>
                            -d '{"name":"John Doe","email":"john@school.edu"}'
                        </code>
                    </div>
                    <a href="/docs/quickstart" class="inline-flex items-center text-green-600 font-semibold hover:text-green-700 transition-colors">
                        Start Now
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- API Reference -->
            <div class="group relative bg-gradient-to-br from-white to-blue-50/50 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-blue-100/50">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">📖 API Reference</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Complete reference documentation with all endpoints, parameters, and response formats.
                    </p>
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-sm">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-3"></span>
                            <span class="text-slate-700">Interactive examples</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="w-3 h-3 bg-blue-500 rounded-full mr-3"></span>
                            <span class="text-slate-700">Response schemas</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <span class="w-3 h-3 bg-purple-500 rounded-full mr-3"></span>
                            <span class="text-slate-700">Error handling</span>
                        </div>
                    </div>
                    <a href="/docs/api" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                        Explore API
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- SDKs & Libraries -->
            <div class="group relative bg-gradient-to-br from-white to-purple-50/50 rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-3 border border-purple-100/50">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-transparent rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-4">💻 SDKs & Libraries</h3>
                    <p class="text-slate-600 mb-6 leading-relaxed">
                        Official SDKs for popular programming languages with built-in best practices.
                    </p>
                    <div class="grid grid-cols-2 gap-2 mb-6">
                        <div class="bg-slate-100 rounded-lg p-2 text-center">
                            <span class="text-xs font-bold text-slate-700">Node.js</span>
                        </div>
                        <div class="bg-slate-100 rounded-lg p-2 text-center">
                            <span class="text-xs font-bold text-slate-700">Python</span>
                        </div>
                        <div class="bg-slate-100 rounded-lg p-2 text-center">
                            <span class="text-xs font-bold text-slate-700">PHP</span>
                        </div>
                        <div class="bg-slate-100 rounded-lg p-2 text-center">
                            <span class="text-xs font-bold text-slate-700">Java</span>
                        </div>
                    </div>
                    <a href="/docs/sdks" class="inline-flex items-center text-purple-600 font-semibold hover:text-purple-700 transition-colors">
                        View SDKs
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Interactive Code Example -->
        <div class="bg-gradient-to-br from-slate-900 to-blue-900 rounded-3xl p-12 shadow-2xl border border-slate-700">
            <div class="text-center mb-8">
                <h3 class="text-3xl font-black text-white mb-4">🧪 API Endpoint Tester</h3>
                <p class="text-white/70 text-lg">Test real School Management System endpoints in real-time</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- API Tester -->
                <div class="bg-black/50 rounded-2xl p-6 border border-white/10">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-white font-bold">Student Enrollment API</h4>
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                    </div>
                    
                    <!-- API Endpoint Selector -->
                    <div class="mb-4">
                        <label class="text-white/80 text-sm font-semibold block mb-2">Select Endpoint:</label>
                        <select id="apiEndpoint" class="w-full bg-slate-800 text-white p-3 rounded-lg border border-white/20 focus:border-blue-400 focus:outline-none">
                            <option value="/api/user/students">GET /api/user/students - List Students</option>
                            <option value="/api/user/teachers">GET /api/user/teachers - List Teachers</option>
                            <option value="/api/academic/classes">GET /api/academic/classes - List Classes</option>
                            <option value="/api/academic/attendance">GET /api/academic/attendance - Student Attendance</option>
                            <option value="/api/academic/weekly-schedule">GET /api/academic/weekly-schedule - Class Schedules</option>
                            <option value="/api/academic/grades">GET /api/academic/grades - Student Grades</option>
                        </select>
                    </div>
                    
                    <!-- Test Button -->
                    <button onclick="testAPI()" class="w-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold py-3 px-6 rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-300 shadow-lg mb-4">
                        🚀 Test API Endpoint
                    </button>
                    
                    <pre class="text-green-400 text-sm overflow-x-auto"><code>// Example: Fetch student list
fetch('/api/user/students', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token
  },
  body: JSON.stringify({
    limit: 10,
    status: 'active'
  })
})
.then(response => response.json())
.then(data => console.log(data));</code></pre>
                </div>
                
                <!-- Response Example -->
                <div class="bg-black/50 rounded-2xl p-6 border border-white/10">
                    <h4 class="text-white font-bold mb-4">Live Response</h4>
                    <div id="apiResponse" class="min-h-[200px]">
                        <pre class="text-blue-300 text-sm overflow-x-auto"><code>{
  "status": "success",
  "data": [
    {
      "slug": "student_001",
      "name": "John Smith",
      "student_number": "STU2024001",
      "email": "john.smith@school.edu",
      "class": "Grade 10-A",
      "status": "active",
      "enrollment_date": "2024-01-15"
    },
    {
      "slug": "student_002", 
      "name": "Sarah Johnson",
      "student_number": "STU2024002",
      "email": "sarah.johnson@school.edu",
      "class": "Grade 10-B",
      "status": "active",
      "enrollment_date": "2024-01-16"
    }
  ],
  "pagination": {
    "current_page": 1,
    "per_page": 10,
    "total": 250
  }
}</code></pre>
                    </div>
                </div>
    "from": "YourBrand", 
    "message": "Hello from SMS API!",
    "status": "queued",
    "created_at": "2024-01-01T10:00:00Z",
    "scheduled_at": "2024-01-01T12:00:00Z",
    "cost": {
      "amount": 0.05,
      "currency": "USD"
    }
  }
}</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Update current time
function updateTime() {
    const now = new Date();
    const timeString = now.toLocaleString('en-US', {
        weekday: 'short',
        year: 'numeric', 
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('currentTime').textContent = timeString;
}

// Test individual endpoint
async function testEndpoint(endpoint) {
    const method = endpoint.includes('students') || endpoint.includes('teachers') ? 'POST' : 'GET';
    
    try {
        const response = await fetch(endpoint, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        alert(`${endpoint}\nStatus: ${response.status}\nResponse: ${JSON.stringify(data, null, 2)}`);
        
    } catch (error) {
        alert(`Error testing ${endpoint}: ${error.message}`);
    }
}

// Test selected endpoint
async function testSelectedEndpoint() {
    const endpoint = document.getElementById('endpointSelect').value;
    const responseDisplay = document.getElementById('responseDisplay');
    
    // Show loading
    responseDisplay.innerHTML = '<div class="text-blue-600">Testing endpoint...</div>';
    
    try {
        const response = await fetch(endpoint, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        responseDisplay.innerHTML = `
            <div class="mb-2 text-sm">
                <span class="font-medium">Status:</span> 
                <span class="text-${response.ok ? 'green' : 'red'}-600">${response.status} ${response.statusText}</span>
            </div>
            <pre class="text-xs text-gray-700 whitespace-pre-wrap">${JSON.stringify(data, null, 2)}</pre>
        `;
        
    } catch (error) {
        responseDisplay.innerHTML = `
            <div class="text-red-600">
                <div class="font-medium">Error:</div>
                <div class="text-sm">${error.message}</div>
            </div>
        `;
    }
}

// Update system status
async function updateSystemStatus() {
    try {
        const response = await fetch('/gateway/health');
        const data = await response.json();
        
        // Update API status
        const apiStatus = document.getElementById('apiStatus');
        const dbStatus = document.getElementById('dbStatus');
        
        if (data.status === 'ok') {
            apiStatus.textContent = 'Operational';
            apiStatus.className = 'text-sm text-green-600 font-medium';
        } else {
            apiStatus.textContent = 'Issues';
            apiStatus.className = 'text-sm text-red-600 font-medium';
        }
        
        if (data.database === 'connected') {
            dbStatus.textContent = 'Connected';
            dbStatus.className = 'text-sm text-blue-600 font-medium';
        } else {
            dbStatus.textContent = 'Disconnected';
            dbStatus.className = 'text-sm text-red-600 font-medium';
        }
        
    } catch (error) {
        console.error('Failed to fetch system status:', error);
    }
}

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    updateTime();
    updateSystemStatus();
    
    // Update time every minute
    setInterval(updateTime, 60000);
    
    // Update system status every 30 seconds
    setInterval(updateSystemStatus, 30000);
});
</script>
@endpush