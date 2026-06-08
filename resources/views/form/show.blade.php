@php
    $formColor = in_array($form->color, ['blue', 'green', 'purple', 'red', 'yellow', 'indigo'], true) ? $form->color : 'blue';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $form->title }} - SITC Campus</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sitc-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/sitc-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <style>
        .form-field {
            transition: all 0.2s ease;
        }
        .form-field:hover {
            transform: translateX(2px);
        }
        .loading-spinner:not(.hidden) {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-left: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen pb-8 sm:pb-12">
    <!-- SITC Branding Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 mb-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/sitc-logo.png') }}" alt="SITC Campus" class="h-10 w-auto">
                </div>
            </div>
        </div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Form Container -->
        <div id="form-container" class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 mb-8 sm:mb-12">
            <!-- Form Header with Brand -->
            <div class="p-8 sm:p-10 border-b-4 border-{{ $formColor }}-500 bg-gradient-to-r from-{{ $formColor }}-50 to-white">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">{{ $form->title }}</h1>
                        @if($form->description)
                            <p class="mt-3 text-base text-gray-600 leading-relaxed">{{ $form->description }}</p>
                        @endif
                    </div>
                    <!-- Optional Form Icon -->
                    <div class="hidden sm:block ml-4">
                        <div class="w-16 h-16 rounded-full bg-{{ $formColor }}-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-{{ $formColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-sm text-gray-500 flex items-center">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    All fields marked with <span class="text-red-500 font-bold mx-1">*</span> are required
                </p>
            </div>

            <!-- Success Message -->
            <div id="success-message" class="hidden mx-8 mt-6 p-5 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-green-900">Success!</h3>
                        <p class="mt-1 text-sm text-green-800">Form submitted successfully! Thank you for your response.</p>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden mx-8 mt-6 p-5 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-900">Error</h3>
                        <p class="mt-1 text-sm text-red-800" id="error-text"></p>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <form id="submission-form" class="p-8 sm:p-10 space-y-8" enctype="multipart/form-data">
                @foreach($form->fields as $field)
                    <div class="form-field bg-gray-50 p-6 rounded-lg border border-gray-200 hover:border-{{ $formColor }}-300 transition-all">
                        <label class="block text-base font-semibold text-gray-800 mb-3">
                            {{ $field->label }}
                            @if($field->required)
                                <span class="text-red-500 ml-1">*</span>
                            @endif
                        </label>

                        @switch($field->type)
                            @case('text')
                            @case('email')
                            @case('number')
                            @case('date')
                                <input type="{{ $field->type }}" 
                                       name="field_{{ $field->id }}" 
                                       class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-{{ $formColor }}-500 focus:border-transparent transition-all shadow-sm"
                                       placeholder="Enter your {{ strtolower($field->label) }}"
                                       {{ $field->required ? 'required' : '' }}>
                                @break

                            @case('textarea')
                                <textarea name="field_{{ $field->id }}" 
                                          rows="5" 
                                          class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-{{ $formColor }}-500 focus:border-transparent transition-all shadow-sm resize-y"
                                          placeholder="Enter your {{ strtolower($field->label) }}"
                                          {{ $field->required ? 'required' : '' }}></textarea>
                                @break

                            @case('select')
                                <select name="field_{{ $field->id }}" 
                                        class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-{{ $formColor }}-500 focus:border-transparent transition-all shadow-sm"
                                        {{ $field->required ? 'required' : '' }}>
                                    <option value="">-- Select an option --</option>
                                    @foreach($field->options as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                @break

                            @case('radio')
                                <div class="space-y-3 bg-white p-4 rounded-lg border border-gray-200">
                                    @foreach($field->options as $option)
                                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                            <input type="radio" 
                                                   name="field_{{ $field->id }}" 
                                                   value="{{ $option }}"
                                                   class="w-5 h-5 text-{{ $formColor }}-600 focus:ring-{{ $formColor }}-500 focus:ring-2"
                                                   {{ $field->required ? 'required' : '' }}>
                                            <span class="text-gray-800 font-medium">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('checkbox')
                                <div class="space-y-3 bg-white p-4 rounded-lg border border-gray-200">
                                    @foreach($field->options as $index => $option)
                                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors">
                                            <input type="checkbox" 
                                                   name="field_{{ $field->id }}[]" 
                                                   value="{{ $option }}"
                                                   class="w-5 h-5 text-{{ $formColor }}-600 focus:ring-{{ $formColor }}-500 focus:ring-2 rounded">
                                            <span class="text-gray-800 font-medium">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('file')
                                <div class="relative">
                                    <input type="file" 
                                           name="field_{{ $field->id }}" 
                                           class="w-full px-4 py-3 bg-white border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-{{ $formColor }}-500 focus:border-transparent transition-all shadow-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-{{ $formColor }}-50 file:text-{{ $formColor }}-700 hover:file:bg-{{ $formColor }}-100"
                                           @if($field->file_settings && isset($field->file_settings['accepted_types']))
                                               accept="{{ $field->file_settings['accepted_types'] }}"
                                           @endif
                                           {{ $field->required ? 'required' : '' }}>
                                </div>
                                @if($field->file_settings)
                                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        @if(isset($field->file_settings['accepted_types']))
                                            Accepted types: <strong class="ml-1">{{ $field->file_settings['accepted_types'] }}</strong>
                                        @endif
                                        @if(isset($field->file_settings['max_size']))
                                            <span class="mx-2">•</span> Max size: <strong class="ml-1">{{ $field->file_settings['max_size'] }}MB</strong>
                                        @endif
                                    </p>
                                @endif
                                @break
                        @endswitch
                    </div>
                @endforeach

                <!-- Submit Button Section -->
                <div class="pt-6 border-t-2 border-gray-200">
                    <!-- Cloudflare Turnstile -->
                    <div class="mb-6 flex justify-center">
                        <div class="cf-turnstile" 
                             data-sitekey="{{ $turnstileSiteKey }}"
                             data-theme="light"
                             data-size="normal"
                             data-callback="onTurnstileSuccess"
                             data-error-callback="onTurnstileError"
                             data-expired-callback="onTurnstileExpired">
                        </div>
                    </div>
                    
                    <button type="submit" 
                            id="submit-btn"
                            class="w-full bg-gradient-to-r from-{{ $formColor }}-600 to-{{ $formColor }}-700 hover:from-{{ $formColor }}-700 hover:to-{{ $formColor }}-800 text-white px-8 py-4 rounded-lg font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none"
                            disabled>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="submit-btn-text">Submit Form</span>
                        <span id="submit-spinner" class="hidden loading-spinner"></span>
                    </button>
                    <div class="mt-4 text-center text-sm text-gray-500">
                        <p id="security-status" class="mb-2">Please complete the security verification above</p>
                        <p>Your response will be recorded securely</p>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <script>
        let turnstileToken = null;
        
        // Turnstile callback functions
        window.onTurnstileSuccess = function(token) {
            turnstileToken = token;
            const submitBtn = document.getElementById('submit-btn');
            const securityStatus = document.getElementById('security-status');
            
            submitBtn.disabled = false;
            securityStatus.textContent = 'Security verification completed ✓';
            securityStatus.className = 'mb-2 text-green-600 font-medium';
        };
        
        window.onTurnstileError = function() {
            turnstileToken = null;
            const submitBtn = document.getElementById('submit-btn');
            const securityStatus = document.getElementById('security-status');
            
            submitBtn.disabled = true;
            securityStatus.textContent = 'Security verification failed. Please try again.';
            securityStatus.className = 'mb-2 text-red-600 font-medium';
        };
        
        window.onTurnstileExpired = function() {
            turnstileToken = null;
            const submitBtn = document.getElementById('submit-btn');
            const securityStatus = document.getElementById('security-status');
            
            submitBtn.disabled = true;
            securityStatus.textContent = 'Security verification expired. Please verify again.';
            securityStatus.className = 'mb-2 text-orange-600 font-medium';
        };

        const form = document.getElementById('submission-form');
        const submitBtn = document.getElementById('submit-btn');
        const submitBtnText = document.getElementById('submit-btn-text');
        const submitSpinner = document.getElementById('submit-spinner');
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Check if Turnstile token is available
            if (!turnstileToken) {
                const securityStatus = document.getElementById('security-status');
                securityStatus.textContent = 'Please complete the security verification first.';
                securityStatus.className = 'mb-2 text-red-600 font-medium';
                return;
            }

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            submitBtnText.textContent = 'Submitting...';
            submitSpinner.classList.remove('hidden');
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');

            // Hide previous messages
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');

            try {
                const formData = new FormData(form);
                
                // Add Turnstile token to form data
                formData.append('cf-turnstile-response', turnstileToken);
                
                const response = await fetch('{{ route('form.submit', $form->slug) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                // Check if response is OK
                if (!response.ok) {
                    if (response.status === 422) {
                        const errorData = await response.json();
                        throw new Error(errorData.message || 'Please check your form inputs and try again.');
                    } else if (response.status === 403) {
                        throw new Error('This form is not accepting submissions at this time.');
                    } else if (response.status === 404) {
                        throw new Error('Form not found. This link may be invalid.');
                    } else {
                        throw new Error('Server error. Please try again later.');
                    }
                }

                const data = await response.json();

                if (data.success) {
                    successMessage.classList.remove('hidden');
                    form.reset();
                    turnstileToken = null;
                    
                    // Reset Turnstile widget
                    if (typeof turnstile !== 'undefined') {
                        turnstile.reset();
                    }
                    
                    
                    // Hide the form container (fields, buttons, etc.)
                    const formContainer = document.getElementById('form-container');
                    formContainer.style.opacity = '0';
                    formContainer.style.transition = 'opacity 0.5s ease-out';
                    
                    setTimeout(() => {
                        formContainer.style.display = 'none';
                    }, 500);
                    
                    // Scroll to top to show success message
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    throw new Error(data.message || 'Submission failed. Please try again.');
                }
            } catch (error) {
                console.error('Submission error:', error);
                
                // Show specific error message
                let errorMsg = 'Failed to submit form. Please try again.';
                if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                    errorMsg = 'Network error. Please check your internet connection and try again.';
                } else if (error.message) {
                    errorMsg = error.message;
                }
                
                errorText.textContent = errorMsg;
                errorMessage.classList.remove('hidden');
                
                // Scroll to top to show error message
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                // Re-enable submit button (will be disabled again until Turnstile is completed)
                submitBtn.disabled = true;
                submitBtnText.textContent = 'Submit Form';
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                
                // Reset Turnstile widget on error
                turnstileToken = null;
                if (typeof turnstile !== 'undefined') {
                    turnstile.reset();
                }
                
                // Reset security status
                const securityStatus = document.getElementById('security-status');
                securityStatus.textContent = 'Please complete the security verification above';
                securityStatus.className = 'mb-2 text-gray-500';
                
                // Auto-hide error after 8 seconds
                setTimeout(() => {
                    errorMessage.classList.add('hidden');
                }, 8000);
            } finally {
                // Hide spinner
                submitSpinner.classList.add('hidden');
            }
        });
    </script>

    <!-- Footer -->
    <footer class="mt-16 border-t border-gray-200 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/sitc-logo.png') }}" alt="SITC Campus" class="h-8 w-auto">
                    <div class="text-sm text-gray-600">
                        <p class="font-semibold">SITC Campus</p>
                        <p class="text-xs">Smart Forms System</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 text-center text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} SITC Campus. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
