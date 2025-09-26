@extends('layouts.app')

@section('title', 'SMS API - Enterprise Messaging Platform')

@section('content')
<!-- Hero Section -->
<div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Dynamic Background -->
    <div class="absolute inset-0">
        <!-- Animated Grid -->
        <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:60px_60px]"></div>
        
        <!-- Floating Orbs -->
        <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-purple-500/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-500/15 rounded-full blur-3xl animate-pulse animation-delay-2000"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl animate-pulse animation-delay-4000"></div>
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-black/30"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Status Badge -->
        <div class="inline-flex items-center px-6 py-3 mb-8 bg-gradient-to-r from-green-500/20 to-blue-500/20 backdrop-blur-xl rounded-full border border-white/20 shadow-2xl">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse shadow-lg shadow-green-400/50"></div>
                <span class="text-white/90 font-medium">🟢 Live & Operational</span>
                <div class="w-1 h-1 bg-white/40 rounded-full"></div>
                <span class="text-green-300 font-semibold">99.9% Uptime</span>
            </div>
        </div>
        
        <!-- Main Heading with Animation -->
        <div class="mb-8">
            <h1 class="text-6xl md:text-8xl font-black mb-4 bg-gradient-to-r from-white via-blue-100 to-purple-200 bg-clip-text text-transparent leading-none tracking-tight">
                SMS API
            </h1>
            <div class="text-4xl md:text-6xl font-bold text-white/80 tracking-wide">
                <span class="bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">Enterprise Platform</span>
            </div>
        </div>
        
        <!-- Subtitle -->
        <p class="text-xl md:text-2xl text-white/70 mb-12 max-w-4xl mx-auto leading-relaxed font-light">
            Power your applications with our <span class="text-white font-semibold">military-grade SMS infrastructure</span><br>
            Trusted by <span class="text-blue-300 font-bold">10,000+</span> developers worldwide
        </p>
        
        <!-- Action Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto mb-16">
            <!-- API Documentation Card -->
            <a href="/docs" class="group relative p-8 bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-xl rounded-3xl border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl shadow-xl">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-purple-500/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 mx-auto mb-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3">📚 API Docs</h3>
                    <p class="text-white/60 mb-4">Complete integration guide with code examples</p>
                    <div class="flex items-center justify-center text-blue-300 font-semibold group-hover:text-blue-200 transition-colors">
                        Get Started
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
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
                Comprehensive SMS API with <span class="text-white font-semibold">RESTful endpoints</span> for all your messaging needs
            </p>
        </div>
        
        <!-- API Endpoints Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <!-- Send SMS Endpoint -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-blue-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-green-500/20 text-green-300 rounded-full text-sm font-bold">POST</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Send SMS</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/v1/sms/send
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Send single or bulk SMS messages with delivery tracking and status callbacks.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                            Authentication required
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                            Rate limit: 1000/min
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Get Message Status -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-purple-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-blue-500/20 text-blue-300 rounded-full text-sm font-bold">GET</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Message Status</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/v1/sms/{id}/status
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Retrieve delivery status and detailed information for any message.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                            Real-time tracking
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                            Webhook support
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Account Balance -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-pink-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm font-bold">GET</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Account Balance</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/v1/account/balance
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Check your account balance and usage statistics in real-time.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                            Usage analytics
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-pink-400 rounded-full mr-2"></span>
                            Cost breakdown
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Webhook Management -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-400/10 to-red-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-orange-500/20 text-orange-300 rounded-full text-sm font-bold">POST</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Webhooks</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/v1/webhooks
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Configure webhooks for real-time delivery status notifications.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-orange-400 rounded-full mr-2"></span>
                            Event-driven
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-red-400 rounded-full mr-2"></span>
                            Secure delivery
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Message Templates -->
            <div class="group relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 transform hover:-translate-y-2 hover:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-400/10 to-blue-400/10 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-14 h-14 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <span class="px-3 py-1 bg-cyan-500/20 text-cyan-300 rounded-full text-sm font-bold">GET</span>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-3">Templates</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/v1/templates
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Manage reusable message templates for consistent branding.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-cyan-400 rounded-full mr-2"></span>
                            Variable support
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-blue-400 rounded-full mr-2"></span>
                            Multi-language
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Analytics & Reports -->
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
                    <h3 class="text-2xl font-black text-white mb-3">Analytics</h3>
                    <p class="text-white/60 mb-4 text-sm font-mono bg-black/20 rounded-lg p-3">
                        /api/v1/analytics
                    </p>
                    <p class="text-white/80 leading-relaxed mb-6">
                        Detailed analytics and reporting for message performance insights.
                    </p>
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-indigo-400 rounded-full mr-2"></span>
                            Custom reports
                        </div>
                        <div class="flex items-center text-sm text-white/70">
                            <span class="w-2 h-2 bg-purple-400 rounded-full mr-2"></span>
                            Export options
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
                        Get up and running in minutes with our step-by-step integration guide and sample code.
                    </p>
                    <div class="bg-slate-900 rounded-xl p-4 mb-6">
                        <code class="text-green-400 text-sm font-mono">
                            curl -X POST https://api.sms.com/v1/send<br/>
                            -H "Authorization: Bearer {key}"<br/>
                            -d '{"to":"+1234567890","message":"Hello!"}'
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
                <h3 class="text-3xl font-black text-white mb-4">Try it Live</h3>
                <p class="text-white/70 text-lg">Interactive code example - modify and test in real-time</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Code Example -->
                <div class="bg-black/50 rounded-2xl p-6 border border-white/10">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-white font-bold">Send SMS Example</h4>
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        </div>
                    </div>
                    <pre class="text-green-400 text-sm overflow-x-auto"><code>const smsClient = require('@sms-api/node');

const client = new smsClient({
  apiKey: 'your-api-key-here'
});

// Send SMS
const result = await client.send({
  to: '+1234567890',
  from: 'YourBrand',
  message: 'Hello from SMS API!',
  options: {
    deliveryReport: true,
    scheduledAt: '2024-01-01T12:00:00Z'
  }
});

console.log('Message sent:', result.id);</code></pre>
                </div>
                
                <!-- Response Example -->
                <div class="bg-black/50 rounded-2xl p-6 border border-white/10">
                    <h4 class="text-white font-bold mb-4">Response</h4>
                    <pre class="text-blue-300 text-sm overflow-x-auto"><code>{
  "success": true,
  "data": {
    "id": "msg_1234567890",
    "to": "+1234567890",
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
// Fetch and display real-time service status
async function updateServiceStatus() {
    try {
        const response = await fetch('/health');
        const data = await response.json();
        
        const statusContainer = document.getElementById('service-status');
        const statusCards = statusContainer.children;
        
        // Update API Status
        const apiCard = statusCards[0];
        const apiIcon = apiCard.querySelector('div div');
        const apiText = apiCard.querySelector('p');
        
        if (data.status === 'ok') {
            apiIcon.className = 'w-3 h-3 bg-green-400 rounded-full';
            apiText.textContent = 'Operational';
            apiText.className = 'text-green-600 font-semibold';
        } else {
            apiIcon.className = 'w-3 h-3 bg-red-400 rounded-full';
            apiText.textContent = 'Issues Detected';
            apiText.className = 'text-red-600 font-semibold';
        }
        
        // Update Database Status
        const dbCard = statusCards[1];
        const dbIcon = dbCard.querySelector('div div');
        const dbText = dbCard.querySelector('p');
        
        if (data.database === 'connected') {
            dbIcon.className = 'w-3 h-3 bg-green-400 rounded-full';
            dbText.textContent = 'Connected';
            dbText.className = 'text-green-600 font-semibold';
        } else {
            dbIcon.className = 'w-3 h-3 bg-red-400 rounded-full';
            dbText.textContent = 'Disconnected';
            dbText.className = 'text-red-600 font-semibold';
        }
        
    } catch (error) {
        console.error('Failed to fetch service status:', error);
    }
}

// Update status on page load
document.addEventListener('DOMContentLoaded', updateServiceStatus);

// Update status every 30 seconds
setInterval(updateServiceStatus, 30000);
</script>
@endpush