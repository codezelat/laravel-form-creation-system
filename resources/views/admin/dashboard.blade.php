@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Welcome back, {{ $adminUsername }}!</h1>
        <p class="mt-2 text-gray-600">Here's what's happening with your forms today.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Forms -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Forms</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalForms }}</p>
                    <p class="text-xs text-gray-500 mt-1">All forms created</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Forms -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Forms</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeForms }}</p>
                    <p class="text-xs text-gray-500 mt-1">Currently accepting responses</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Submissions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Submissions</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalSubmissions }}</p>
                    <p class="text-xs text-gray-500 mt-1">Responses received</p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <a href="{{ route('admin.forms.create') }}" class="group bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-sm p-8 hover:shadow-lg transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white bg-opacity-20 rounded-xl flex items-center justify-center group-hover:bg-opacity-30 transition">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-white">Create New Form</h3>
                    <p class="text-blue-100 mt-1">Build a new form with our drag & drop builder</p>
                </div>
                <svg class="w-6 h-6 text-white group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>

        <a href="{{ route('admin.forms.index') }}" class="group bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-lg transition-all transform hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:shadow-md transition">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold text-gray-900">View All Forms</h3>
                    <p class="text-gray-600 mt-1">Manage, edit, and view submissions</p>
                </div>
                <svg class="w-6 h-6 text-gray-400 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Recent Forms -->
    @if($recentForms->count() > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Recent Forms</h2>
                        <p class="text-sm text-gray-600 mt-1">Your latest created forms</p>
                    </div>
                    <a href="{{ route('admin.forms.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">
                        View all →
                    </a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($recentForms as $form)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-{{ $form->color }}-400 to-{{ $form->color }}-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">{{ $form->title }}</h3>
                                    <p class="text-sm text-gray-500 truncate mt-1">{{ $form->description }}</p>
                                    <div class="flex items-center space-x-3 mt-2">
                                        @if($form->status === 'published')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span>
                                                Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5"></span>
                                                Draft
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500">
                                            Created {{ $form->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                @if($form->status === 'published')
                                    <a href="{{ route('admin.forms.analytics', $form->id) }}" 
                                       class="px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        View Analytics
                                    </a>
                                @else
                                    <a href="{{ route('admin.forms.create') }}?edit={{ $form->id }}" 
                                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                        Continue Editing
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection
