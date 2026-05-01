@extends('layouts.admin')

@section('title', 'Submission #' . $submission->id)

@section('content')
    @php
        $formColor = in_array($form->color, ['blue', 'green', 'purple', 'red', 'yellow', 'indigo'], true) ? $form->color : 'blue';
    @endphp

    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center space-x-2 text-sm text-gray-500">
            <a href="{{ route('admin.forms.index') }}" class="hover:text-gray-700">All Forms</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <a href="{{ route('admin.forms.analytics', $form->id) }}" class="hover:text-gray-700">{{ $form->title }}</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-gray-900 font-medium">Submission #{{ $submission->id }}</span>
        </nav>
    </div>

    <!-- Form Info Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-{{ $formColor }}-400 to-{{ $formColor }}-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $form->title }}</h1>
                <p class="text-gray-500 mt-1">Submission #{{ $submission->id }}</p>
            </div>
        </div>
    </div>

    <!-- Submission Metadata -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Submission Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Submitted At</p>
                    <p class="text-sm text-gray-900 font-semibold mt-1">{{ $submission->submitted_at->format('F d, Y') }}</p>
                    <p class="text-sm text-gray-500">{{ $submission->submitted_at->format('h:i A') }}</p>
                </div>
            </div>

            <div class="flex items-start space-x-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">IP Address</p>
                    <p class="text-sm text-gray-900 font-semibold mt-1">{{ $submission->ip_address }}</p>
                </div>
            </div>

            <div class="flex items-start space-x-3 md:col-span-2">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">User Agent</p>
                    <p class="text-sm text-gray-900 mt-1 break-all">{{ $submission->user_agent }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Data -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Form Responses</h2>
        
        @php
            $submissionData = is_string($submission->submission_data) ? json_decode($submission->submission_data, true) : $submission->submission_data;
            $files = is_string($submission->files) ? json_decode($submission->files, true) : ($submission->files ?? []);
        @endphp

        <div class="space-y-6">
            @foreach($form->fields as $field)
                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">
                        {{ $field->label }}
                        @if($field->required)
                            <span class="text-red-500">*</span>
                        @endif
                    </label>
                    
                    @php
                        $value = $submission->getFieldValue($field);
                    @endphp

                    @if($field->type === 'file')
                        @php
                            $filePath = $submission->getFieldFile($field);
                        @endphp
                        @if($filePath)
                            <div class="mt-2">
                                @php
                                    $fileName = basename($filePath);
                                @endphp
                                <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $fileName }}</p>
                                        <p class="text-xs text-gray-500">Uploaded file</p>
                                    </div>
                                    <a href="{{ Storage::url($filePath) }}" 
                                       target="_blank"
                                       download
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic bg-gray-50 px-4 py-3 rounded-lg">No file uploaded</p>
                        @endif
                    @elseif(in_array($field->type, ['checkbox', 'radio']))
                        @if(is_array($value))
                            <div class="mt-2 space-y-2">
                                @foreach($value as $selectedValue)
                                    <div class="flex items-center space-x-2 bg-green-50 px-4 py-2 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-sm text-gray-900 font-medium">{{ $selectedValue }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($value)
                            <div class="flex items-center space-x-2 mt-2 bg-green-50 px-4 py-2 rounded-lg">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm text-gray-900 font-medium">{{ $value }}</span>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic bg-gray-50 px-4 py-3 rounded-lg">Not answered</p>
                        @endif
                    @elseif($field->type === 'textarea')
                        @if($value)
                            <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $value }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic bg-gray-50 px-4 py-3 rounded-lg">Not answered</p>
                        @endif
                    @else
                        @if($value)
                            <div class="mt-2 px-4 py-3 bg-blue-50 rounded-lg border border-blue-200">
                                <p class="text-sm text-gray-900 font-medium">{{ $value }}</p>
                            </div>
                        @else
                            <p class="text-sm text-gray-500 italic bg-gray-50 px-4 py-3 rounded-lg">Not answered</p>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-between">
        <a href="{{ route('admin.forms.analytics', $form->id) }}" 
           class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Analytics
        </a>
    </div>
@endsection
