<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission #{{ $submission->id }} - {{ $form->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-800">Submission Details</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.forms.analytics', $form->id) }}" class="text-gray-600 hover:text-gray-900">
                        ← Back to Analytics
                    </a>
                    <span class="text-gray-400">|</span>
                    <span class="text-gray-600">{{ $adminUsername }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Form Info Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-{{ $form->color }}-400 to-{{ $form->color }}-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $form->title }}</h2>
                    <p class="text-gray-500 mt-1">Submission #{{ $submission->id }}</p>
                </div>
            </div>
        </div>

        <!-- Submission Metadata -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Submission Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Submitted At</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $submission->submitted_at->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-500">{{ $submission->submitted_at->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">IP Address</p>
                        <p class="text-sm text-gray-900 mt-1">{{ $submission->ip_address }}</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 md:col-span-2">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Form Responses</h3>
            
            @php
                $submissionData = is_string($submission->submission_data) ? json_decode($submission->submission_data, true) : $submission->submission_data;
                $files = is_string($submission->files) ? json_decode($submission->files, true) : ($submission->files ?? []);
            @endphp

            <div class="space-y-6">
                @foreach($form->fields as $field)
                    <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $field->label }}
                                    @if($field->required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>
                                
                                @php
                                    $fieldKey = 'field_' . $field->id;
                                    $value = $submissionData[$fieldKey] ?? null;
                                @endphp

                                @if($field->type === 'file')
                                    @if(isset($files[$fieldKey]))
                                        <div class="mt-2">
                                            @php
                                                $filePath = $files[$fieldKey];
                                                $fileName = basename($filePath);
                                            @endphp
                                            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                </svg>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $fileName }}</p>
                                                    <p class="text-xs text-gray-500">Uploaded file</p>
                                                </div>
                                                <a href="{{ Storage::url($filePath) }}" 
                                                   target="_blank"
                                                   download
                                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">No file uploaded</p>
                                    @endif
                                @elseif(in_array($field->type, ['checkbox', 'radio']))
                                    @if(is_array($value))
                                        <div class="mt-2 space-y-2">
                                            @foreach($value as $selectedValue)
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm text-gray-900">{{ $selectedValue }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($value)
                                        <div class="flex items-center space-x-2 mt-2">
                                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm text-gray-900">{{ $value }}</span>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Not answered</p>
                                    @endif
                                @elseif($field->type === 'textarea')
                                    @if($value)
                                        <div class="mt-2 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $value }}</p>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Not answered</p>
                                    @endif
                                @else
                                    @if($value)
                                        <p class="mt-2 text-sm text-gray-900 font-medium">{{ $value }}</p>
                                    @else
                                        <p class="text-sm text-gray-500 italic">Not answered</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('admin.forms.analytics', $form->id) }}" 
               class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Analytics
            </a>
        </div>
    </div>
</body>
</html>
