<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $form->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Form Header -->
            <div class="p-6 border-b border-gray-200 bg-{{ $form->color }}-50">
                <h1 class="text-3xl font-bold text-gray-900">{{ $form->title }}</h1>
                @if($form->description)
                    <p class="mt-2 text-gray-600">{{ $form->description }}</p>
                @endif
            </div>

            <!-- Success Message -->
            <div id="success-message" class="hidden p-6 bg-green-50 border-b border-green-200">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span class="text-green-800 font-medium">Form submitted successfully! Thank you for your response.</span>
                </div>
            </div>

            <!-- Error Message -->
            <div id="error-message" class="hidden p-6 bg-red-50 border-b border-red-200">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="text-red-800 font-medium" id="error-text"></span>
                </div>
            </div>

            <!-- Form Fields -->
            <form id="submission-form" class="p-6 space-y-6" enctype="multipart/form-data">
                @foreach($form->fields as $field)
                    <div class="form-field">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            {{ $field->label }}
                            @if($field->required)
                                <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @switch($field->type)
                            @case('text')
                            @case('email')
                            @case('number')
                            @case('date')
                                <input type="{{ $field->type }}" 
                                       name="field_{{ $field->id }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ $form->color }}-500"
                                       {{ $field->required ? 'required' : '' }}>
                                @break

                            @case('textarea')
                                <textarea name="field_{{ $field->id }}" 
                                          rows="4" 
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ $form->color }}-500"
                                          {{ $field->required ? 'required' : '' }}></textarea>
                                @break

                            @case('select')
                                <select name="field_{{ $field->id }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ $form->color }}-500"
                                        {{ $field->required ? 'required' : '' }}>
                                    <option value="">Choose an option</option>
                                    @foreach($field->options as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                                @break

                            @case('radio')
                                <div class="space-y-2">
                                    @foreach($field->options as $option)
                                        <label class="flex items-center space-x-2">
                                            <input type="radio" 
                                                   name="field_{{ $field->id }}" 
                                                   value="{{ $option }}"
                                                   class="text-{{ $form->color }}-600 focus:ring-{{ $form->color }}-500"
                                                   {{ $field->required ? 'required' : '' }}>
                                            <span class="text-gray-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('checkbox')
                                <div class="space-y-2">
                                    @foreach($field->options as $index => $option)
                                        <label class="flex items-center space-x-2">
                                            <input type="checkbox" 
                                                   name="field_{{ $field->id }}[]" 
                                                   value="{{ $option }}"
                                                   class="text-{{ $form->color }}-600 focus:ring-{{ $form->color }}-500">
                                            <span class="text-gray-700">{{ $option }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @break

                            @case('file')
                                <input type="file" 
                                       name="field_{{ $field->id }}" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ $form->color }}-500"
                                       @if($field->file_settings && isset($field->file_settings['accepted_types']))
                                           accept="{{ $field->file_settings['accepted_types'] }}"
                                       @endif
                                       {{ $field->required ? 'required' : '' }}>
                                @if($field->file_settings)
                                    <p class="mt-1 text-xs text-gray-500">
                                        @if(isset($field->file_settings['accepted_types']))
                                            Accepted: {{ $field->file_settings['accepted_types'] }}
                                        @endif
                                        @if(isset($field->file_settings['max_size']))
                                            | Max size: {{ $field->file_settings['max_size'] }}MB
                                        @endif
                                    </p>
                                @endif
                                @break
                        @endswitch
                    </div>
                @endforeach

                <div class="pt-4">
                    <button type="submit" 
                            id="submit-btn"
                            class="w-full bg-{{ $form->color }}-600 hover:bg-{{ $form->color }}-700 text-white px-6 py-3 rounded-md font-medium transition">
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('submission-form');
        const submitBtn = document.getElementById('submit-btn');
        const successMessage = document.getElementById('success-message');
        const errorMessage = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

            // Hide previous messages
            successMessage.classList.add('hidden');
            errorMessage.classList.add('hidden');

            try {
                const formData = new FormData(form);
                
                const response = await fetch('{{ route('form.submit', $form->slug) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    successMessage.classList.remove('hidden');
                    form.reset();
                    
                    // Scroll to success message
                    successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    throw new Error(data.message || 'Submission failed');
                }
            } catch (error) {
                console.error('Submission error:', error);
                errorText.textContent = error.message || 'Failed to submit form. Please try again.';
                errorMessage.classList.remove('hidden');
                
                // Scroll to error message
                errorMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Submit';
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });
    </script>
</body>
</html>
