<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - SITC Form Builder</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sitc-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/sitc-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3">
                        <img src="{{ asset('images/sitc-logo.png') }}" alt="SITC Campus" class="h-7 w-auto">
                        <span class="text-lg font-semibold text-gray-900 hidden sm:block">Form Builder</span>
                    </a>
                    
                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-1">
                        <a href="{{ route('admin.dashboard') }}" 
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('admin.forms.index') }}" 
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.forms.*') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            All Forms
                        </a>
                        <a href="{{ route('admin.forms.create') }}" 
                           class="px-4 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.forms.create') ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg class="w-4 h-4 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Form
                        </a>
                    </div>
                </div>

                <!-- Right side: User menu -->
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600 hidden sm:block">
                        <svg class="w-4 h-4 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $adminUsername ?? session('admin_username', 'Admin') }}
                    </span>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="pt-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
