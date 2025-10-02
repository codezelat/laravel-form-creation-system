<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Form Builder - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900">Form Builder</h1>
                    <span class="text-sm text-gray-500" id="auto-save-status">Changes saved</span>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="preview-btn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                        Preview
                    </button>
                    <button id="settings-btn" class="hidden bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                        Form Settings
                    </button>
                    <button id="publish-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm font-medium transition">
                        Publish Form
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Form Settings Panel -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 sticky top-24">
                    <!-- Form Settings -->
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Form Settings</h3>
                        
                        <!-- Form Title -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Form Title</label>
                            <input type="text" id="form-title" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   value="Untitled Form" placeholder="Enter form title">
                        </div>

                        <!-- Form Description -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="form-description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                      placeholder="Add a description for your form"></textarea>
                        </div>

                        <!-- Form Color Theme -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Theme Color</label>
                            <div class="grid grid-cols-6 gap-2">
                                <button class="w-8 h-8 rounded-full bg-blue-500 ring-2 ring-blue-500 ring-offset-2" data-color="blue"></button>
                                <button class="w-8 h-8 rounded-full bg-green-500 hover:ring-2 hover:ring-green-500 hover:ring-offset-2" data-color="green"></button>
                                <button class="w-8 h-8 rounded-full bg-purple-500 hover:ring-2 hover:ring-purple-500 hover:ring-offset-2" data-color="purple"></button>
                                <button class="w-8 h-8 rounded-full bg-red-500 hover:ring-2 hover:ring-red-500 hover:ring-offset-2" data-color="red"></button>
                                <button class="w-8 h-8 rounded-full bg-yellow-500 hover:ring-2 hover:ring-yellow-500 hover:ring-offset-2" data-color="yellow"></button>
                                <button class="w-8 h-8 rounded-full bg-indigo-500 hover:ring-2 hover:ring-indigo-500 hover:ring-offset-2" data-color="indigo"></button>
                            </div>
                        </div>
                    </div>

                    <!-- Add Field Types -->
                    <div class="p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Add Fields</h4>
                        <div class="space-y-2">
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="text">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                </svg>
                                Short Text
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="textarea">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                Long Text
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="select">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                </svg>
                                Dropdown
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="radio">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1 1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                                </svg>
                                Multiple Choice
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="checkbox">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Checkboxes
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="file">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                File Upload
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="email">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="number">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                </svg>
                                Number
                            </button>
                            <button class="add-field-btn w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md flex items-center" data-type="date">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Date
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Builder Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <!-- Form Header -->
                    <div class="p-6 border-b border-gray-200 bg-blue-50" id="form-header">
                        <h2 class="text-2xl font-bold text-gray-900" id="form-title-display">Untitled Form</h2>
                        <p class="text-gray-600 mt-2" id="form-description-display">Add a description for your form</p>
                    </div>

                    <!-- Form Fields -->
                    <div class="p-6" id="form-fields">
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No fields yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding a field from the panel on the left.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Preview Modal -->
    <div id="preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 transition-opacity">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Form Preview</h3>
                    <button id="close-preview" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="preview-content" class="p-6">
                    <!-- Preview content will be generated here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Publish Modal -->
    <div id="publish-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 transition-opacity">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-xl font-bold text-gray-900">Publish Form</h3>
                    <p class="text-sm text-gray-500 mt-1">Configure your form settings before publishing</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Custom Slug -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Custom URL Slug (Optional)
                        </label>
                        <input type="text" id="custom-slug" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="my-custom-form">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate</p>
                    </div>
                    
                    <!-- Visibility -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Form Visibility
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="visibility" value="public" checked
                                       class="mt-1 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">Public</div>
                                    <div class="text-sm text-gray-500">Anyone with the link can submit</div>
                                </div>
                            </label>
                            <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                <input type="radio" name="visibility" value="only_me"
                                       class="mt-1 text-blue-600 focus:ring-blue-500">
                                <div class="ml-3">
                                    <div class="font-medium text-gray-900">Private (Only Me)</div>
                                    <div class="text-sm text-gray-500">Only you can submit responses</div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="p-6 bg-gray-50 rounded-b-xl flex justify-end space-x-3">
                    <button id="cancel-publish" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button id="confirm-publish" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Publish Form
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
                <div class="p-8 text-center">
                    <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Form Published!</h3>
                    <p class="text-gray-600 mb-6" id="success-message-text">Your form is now live and ready to accept responses.</p>
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <p class="text-sm text-gray-600 mb-2">Share this URL:</p>
                        <div class="flex items-center space-x-2">
                            <input type="text" id="form-url" readonly
                                   class="flex-1 px-3 py-2 bg-white border border-gray-300 rounded text-sm">
                            <button id="copy-url" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm font-medium">
                                Copy
                            </button>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <button id="view-form" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            View Published Form
                        </button>
                        <button id="close-success" class="w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Toast -->
    <div id="error-toast" class="fixed top-4 right-4 hidden z-50 transition-all transform translate-x-full">
        <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-start max-w-md">
            <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="font-medium">Error</p>
                <p class="text-sm" id="error-toast-message"></p>
            </div>
            <button id="close-error-toast" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let formData = {
                title: 'Untitled Form',
                description: '',
                color: 'blue',
                fields: []
            };
            let fieldCounter = 0;
            let autoSaveTimeout;

            // Form settings handlers
            const formTitle = document.getElementById('form-title');
            const formDescription = document.getElementById('form-description');
            const formTitleDisplay = document.getElementById('form-title-display');
            const formDescriptionDisplay = document.getElementById('form-description-display');
            const autoSaveStatus = document.getElementById('auto-save-status');
            const formHeader = document.getElementById('form-header');

            // Auto-save function
            function autoSave() {
                clearTimeout(autoSaveTimeout);
                autoSaveStatus.textContent = 'Saving...';
                autoSaveStatus.className = 'text-sm text-yellow-500';
                
                autoSaveTimeout = setTimeout(async () => {
                    try {
                        // Validate before saving
                        if (!formData.title || formData.title.trim() === '') {
                            autoSaveStatus.textContent = 'Title required';
                            autoSaveStatus.className = 'text-sm text-red-500';
                            return;
                        }
                        
                        if (formData.fields.length === 0) {
                            autoSaveStatus.textContent = 'Add at least one field';
                            autoSaveStatus.className = 'text-sm text-orange-500';
                            return;
                        }
                        
                        const response = await fetch('{{ route('admin.forms.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                id: formData.id,
                                title: formData.title,
                                description: formData.description,
                                color: formData.color,
                                fields: formData.fields
                            })
                        });

                        if (!response.ok) {
                            if (response.status === 422) {
                                const errorData = await response.json();
                                throw new Error(errorData.message || 'Validation error');
                            } else {
                                throw new Error('Server error');
                            }
                        }

                        const data = await response.json();
                        
                        if (data.success) {
                            // Store form ID for future updates
                            formData.id = data.form_id;
                            autoSaveStatus.textContent = 'Changes saved ✓';
                            autoSaveStatus.className = 'text-sm text-green-500';
                        } else {
                            throw new Error(data.message || 'Failed to save');
                        }
                    } catch (error) {
                        console.error('Auto-save error:', error);
                        autoSaveStatus.textContent = 'Failed to save - ' + (error.message || 'Try again');
                        autoSaveStatus.className = 'text-sm text-red-500';
                        
                        // Show error toast for critical errors
                        if (error.message && error.message !== 'Failed to save') {
                            showError(error.message);
                        }
                    }
                }, 1000);
            }

            // Form title change handler
            formTitle.addEventListener('input', function() {
                formData.title = this.value || 'Untitled Form';
                formTitleDisplay.textContent = formData.title;
                autoSave();
            });

            // Form description change handler
            formDescription.addEventListener('input', function() {
                formData.description = this.value;
                formDescriptionDisplay.textContent = this.value || 'Add a description for your form';
                autoSave();
            });

            // Color theme handlers
            const colorClasses = {
                blue: { header: 'p-6 border-b border-gray-200 bg-blue-50', ring: 'ring-blue-500' },
                green: { header: 'p-6 border-b border-gray-200 bg-green-50', ring: 'ring-green-500' },
                purple: { header: 'p-6 border-b border-gray-200 bg-purple-50', ring: 'ring-purple-500' },
                red: { header: 'p-6 border-b border-gray-200 bg-red-50', ring: 'ring-red-500' },
                yellow: { header: 'p-6 border-b border-gray-200 bg-yellow-50', ring: 'ring-yellow-500' },
                indigo: { header: 'p-6 border-b border-gray-200 bg-indigo-50', ring: 'ring-indigo-500' }
            };

            document.querySelectorAll('[data-color]').forEach(button => {
                button.addEventListener('click', function() {
                    const color = this.dataset.color;
                    formData.color = color;
                    
                    // Update active color
                    document.querySelectorAll('[data-color]').forEach(btn => {
                        btn.classList.remove('ring-2', 'ring-offset-2');
                        // Remove all possible ring colors
                        Object.values(colorClasses).forEach(colorClass => {
                            btn.classList.remove(colorClass.ring);
                        });
                    });
                    this.classList.add('ring-2', 'ring-offset-2', colorClasses[color].ring);
                    
                    // Update form header color
                    formHeader.className = colorClasses[color].header;
                    
                    autoSave();
                });
            });

            // Add field handlers
            document.querySelectorAll('.add-field-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const fieldType = this.dataset.type;
                    addField(fieldType);
                });
            });

            function addField(type) {
                fieldCounter++;
                const field = {
                    id: fieldCounter,
                    type: type,
                    label: getFieldLabel(type),
                    required: false,
                    options: type === 'select' || type === 'radio' || type === 'checkbox' ? ['Option 1'] : null,
                    fileSettings: type === 'file' ? {
                        allowedTypes: ['pdf', 'doc', 'docx', 'jpg', 'png'],
                        maxSize: 5 // MB
                    } : null
                };

                formData.fields.push(field);
                renderField(field);
                autoSave();
            }

            function getFieldLabel(type) {
                const labels = {
                    text: 'Short Text',
                    textarea: 'Long Text',
                    select: 'Dropdown',
                    radio: 'Multiple Choice',
                    checkbox: 'Checkboxes',
                    file: 'File Upload',
                    email: 'Email',
                    number: 'Number',
                    date: 'Date'
                };
                return labels[type] || 'Field';
            }

            function renderField(field) {
                const fieldsContainer = document.getElementById('form-fields');
                
                // Only remove empty state if this is the very first field
                if (formData.fields.length === 1 && fieldsContainer.querySelector('.text-center')) {
                    fieldsContainer.innerHTML = '';
                }

                const fieldHTML = createFieldHTML(field);
                fieldsContainer.insertAdjacentHTML('beforeend', fieldHTML);
                
                // Add event listeners for the new field
                addFieldEventListeners(field.id);
            }

            function updateField(field) {
                const existingField = document.querySelector(`[data-field-id="${field.id}"]`);
                if (existingField) {
                    const fieldHTML = createFieldHTML(field);
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = fieldHTML;
                    const newField = tempDiv.firstElementChild;
                    
                    existingField.parentNode.replaceChild(newField, existingField);
                    addFieldEventListeners(field.id);
                }
            }

            function rerenderAllFields() {
                const fieldsContainer = document.getElementById('form-fields');
                fieldsContainer.innerHTML = '';
                
                if (formData.fields.length === 0) {
                    fieldsContainer.innerHTML = `
                        <div class="text-center py-12 text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No fields yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding a field from the panel on the left.</p>
                        </div>
                    `;
                } else {
                    formData.fields.forEach(field => {
                        const fieldHTML = createFieldHTML(field);
                        fieldsContainer.insertAdjacentHTML('beforeend', fieldHTML);
                        addFieldEventListeners(field.id);
                    });
                }
                
                // Re-initialize sortable after re-rendering
                if (typeof initializeSortable === 'function') {
                    initializeSortable();
                }
            }

            function createFieldHTML(field) {
                let fieldContent = '';
                
                switch(field.type) {
                    case 'text':
                    case 'email':
                    case 'number':
                        fieldContent = `<input type="${field.type}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter ${field.label.toLowerCase()}" disabled>`;
                        break;
                    case 'textarea':
                        fieldContent = `<textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter ${field.label.toLowerCase()}" disabled></textarea>`;
                        break;
                    case 'date':
                        fieldContent = `<input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md" disabled>`;
                        break;
                    case 'select':
                        fieldContent = `<select class="w-full px-3 py-2 border border-gray-300 rounded-md" disabled>
                            ${field.options.map(option => `<option>${option}</option>`).join('')}
                        </select>`;
                        break;
                    case 'radio':
                        fieldContent = field.options.map(option => `
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="field_${field.id}" class="text-blue-600" disabled>
                                <span>${option}</span>
                            </label>
                        `).join('');
                        break;
                    case 'checkbox':
                        fieldContent = field.options.map(option => `
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="text-blue-600" disabled>
                                <span>${option}</span>
                            </label>
                        `).join('');
                        break;
                    case 'file':
                        fieldContent = `
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">Upload a file</p>
                                <p class="text-xs text-gray-400">Allowed: ${field.fileSettings.allowedTypes.join(', ')} (Max: ${field.fileSettings.maxSize}MB)</p>
                            </div>
                        `;
                        break;
                }

                return `
                    <div class="border border-gray-200 rounded-lg p-4 mb-4 relative field-item" data-field-id="${field.id}">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <input type="text" class="field-label text-lg font-medium bg-transparent border-none focus:outline-none focus:ring-0 w-full" 
                                       value="${field.label}" data-field-id="${field.id}">
                                <label class="flex items-center mt-2 text-sm text-gray-600">
                                    <input type="checkbox" class="field-required mr-2" data-field-id="${field.id}" ${field.required ? 'checked' : ''}>
                                    Required
                                </label>
                            </div>
                            <div class="flex space-x-2">
                                <button class="field-settings-btn text-gray-400 hover:text-gray-600" data-field-id="${field.id}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                                <button class="field-delete-btn text-red-400 hover:text-red-600" data-field-id="${field.id}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5m-11 6v4m0 0h4m-4 0l5-5m11 1v4m0 0h-4m4 0l-5-5"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="field-content">
                            ${fieldContent}
                        </div>
                        ${(field.type === 'select' || field.type === 'radio' || field.type === 'checkbox') ? `
                            <div class="mt-3 space-y-2 field-options" data-field-id="${field.id}">
                                ${field.options.map((option, index) => `
                                    <div class="flex items-center space-x-2">
                                        <input type="text" class="option-input flex-1 px-2 py-1 border border-gray-300 rounded text-sm" 
                                               value="${option}" data-option-index="${index}">
                                        <button class="remove-option text-red-400 hover:text-red-600" data-option-index="${index}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                `).join('')}
                                <button class="add-option text-sm text-blue-600 hover:text-blue-700">+ Add option</button>
                            </div>
                        ` : ''}
                        ${field.type === 'file' ? `
                            <div class="mt-3 p-3 bg-gray-50 rounded">
                                <div class="mb-2">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Allowed file types:</label>
                                    <input type="text" class="file-types w-full px-2 py-1 border border-gray-300 rounded text-sm" 
                                           value="${field.fileSettings.allowedTypes.join(', ')}" placeholder="pdf, doc, jpg, png">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Max file size (MB):</label>
                                    <input type="number" class="file-size w-full px-2 py-1 border border-gray-300 rounded text-sm" 
                                           value="${field.fileSettings.maxSize}" min="1" max="100">
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            function addFieldEventListeners(fieldId) {
                const fieldElement = document.querySelector(`[data-field-id="${fieldId}"]`);
                
                // Label change
                fieldElement.querySelector('.field-label').addEventListener('input', function() {
                    const field = formData.fields.find(f => f.id == fieldId);
                    field.label = this.value;
                    autoSave();
                });

                // Required checkbox
                fieldElement.querySelector('.field-required').addEventListener('change', function() {
                    const field = formData.fields.find(f => f.id == fieldId);
                    field.required = this.checked;
                    autoSave();
                });

                // Delete field
                fieldElement.querySelector('.field-delete-btn').addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this field?')) {
                        formData.fields = formData.fields.filter(f => f.id != fieldId);
                        rerenderAllFields();
                        autoSave();
                    }
                });

                // Options handling for select, radio, checkbox
                const field = formData.fields.find(f => f.id == fieldId);
                if (field.type === 'select' || field.type === 'radio' || field.type === 'checkbox') {
                    const optionsContainer = fieldElement.querySelector('.field-options');
                    
                    // Option input changes
                    optionsContainer.addEventListener('input', function(e) {
                        if (e.target.classList.contains('option-input')) {
                            const optionIndex = e.target.dataset.optionIndex;
                            field.options[optionIndex] = e.target.value;
                            autoSave();
                        }
                    });

                    // Remove option
                    optionsContainer.addEventListener('click', function(e) {
                        if (e.target.closest('.remove-option')) {
                            const optionIndex = e.target.closest('.remove-option').dataset.optionIndex;
                            field.options.splice(optionIndex, 1);
                            updateField(field);
                            autoSave();
                        }
                    });

                    // Add option
                    optionsContainer.querySelector('.add-option').addEventListener('click', function() {
                        field.options.push(`Option ${field.options.length + 1}`);
                        updateField(field);
                        autoSave();
                    });
                }

                // File settings
                if (field.type === 'file') {
                    fieldElement.querySelector('.file-types').addEventListener('input', function() {
                        field.fileSettings.allowedTypes = this.value.split(',').map(type => type.trim());
                        autoSave();
                    });

                    fieldElement.querySelector('.file-size').addEventListener('input', function() {
                        field.fileSettings.maxSize = parseInt(this.value);
                        autoSave();
                    });
                }
            }

            // Sortable fields - initialize once
            let sortableInstance;
            
            function initializeSortable() {
                if (sortableInstance) {
                    sortableInstance.destroy();
                }
                
                sortableInstance = new Sortable(document.getElementById('form-fields'), {
                    handle: '.drag-handle',
                    animation: 150,
                    filter: '.text-center', // Exclude empty state div
                    onEnd: function(evt) {
                        // Skip if empty state was involved
                        if (evt.item.classList.contains('text-center')) return;
                        
                        // Update field order in formData
                        const movedField = formData.fields.splice(evt.oldIndex, 1)[0];
                        formData.fields.splice(evt.newIndex, 0, movedField);
                        autoSave();
                    }
                });
            }
            
            // Initialize sortable
            initializeSortable();

            // Preview functionality
            document.getElementById('preview-btn').addEventListener('click', function() {
                generatePreview();
                document.getElementById('preview-modal').classList.remove('hidden');
            });

            document.getElementById('close-preview').addEventListener('click', function() {
                document.getElementById('preview-modal').classList.add('hidden');
            });

            function generatePreview() {
                const previewContent = document.getElementById('preview-content');
                let fieldsHTML = '';

                formData.fields.forEach(field => {
                    let fieldHTML = '';
                    
                    switch(field.type) {
                        case 'text':
                        case 'email':
                        case 'number':
                            fieldHTML = `<input type="${field.type}" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter ${field.label.toLowerCase()}">`;
                            break;
                        case 'textarea':
                            fieldHTML = `<textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Enter ${field.label.toLowerCase()}"></textarea>`;
                            break;
                        case 'date':
                            fieldHTML = `<input type="date" class="w-full px-3 py-2 border border-gray-300 rounded-md">`;
                            break;
                        case 'select':
                            fieldHTML = `<select class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                <option>Choose an option</option>
                                ${field.options.map(option => `<option>${option}</option>`).join('')}
                            </select>`;
                            break;
                        case 'radio':
                            fieldHTML = field.options.map(option => `
                                <label class="flex items-center space-x-2">
                                    <input type="radio" name="preview_field_${field.id}" class="text-blue-600">
                                    <span>${option}</span>
                                </label>
                            `).join('');
                            break;
                        case 'checkbox':
                            fieldHTML = field.options.map(option => `
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" class="text-blue-600">
                                    <span>${option}</span>
                                </label>
                            `).join('');
                            break;
                        case 'file':
                            fieldHTML = `<input type="file" class="w-full px-3 py-2 border border-gray-300 rounded-md">`;
                            break;
                    }

                    fieldsHTML += `
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                ${field.label} ${field.required ? '<span class="text-red-500">*</span>' : ''}
                            </label>
                            ${fieldHTML}
                        </div>
                    `;
                });

                previewContent.innerHTML = `
                    <div class="border-b border-gray-200 pb-4 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">${formData.title}</h2>
                        ${formData.description ? `<p class="text-gray-600 mt-2">${formData.description}</p>` : ''}
                    </div>
                    ${fieldsHTML}
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium">
                        Submit
                    </button>
                `;
            }

            // Modal helpers
            function showModal(modalId) {
                document.getElementById(modalId).classList.remove('hidden');
            }
            
            function hideModal(modalId) {
                document.getElementById(modalId).classList.add('hidden');
            }
            
            function showError(message) {
                const errorToast = document.getElementById('error-toast');
                const errorMessage = document.getElementById('error-toast-message');
                errorMessage.textContent = message;
                errorToast.classList.remove('hidden', 'translate-x-full');
                setTimeout(() => {
                    errorToast.classList.add('translate-x-full');
                    setTimeout(() => errorToast.classList.add('hidden'), 300);
                }, 5000);
            }

            // Publish button handler
            const publishBtn = document.getElementById('publish-btn');
            const publishModal = document.getElementById('publish-modal');
            const cancelPublish = document.getElementById('cancel-publish');
            const confirmPublish = document.getElementById('confirm-publish');
            
            publishBtn.addEventListener('click', function() {
                if (!formData.id) {
                    showError('Please save the form before publishing. Add at least one field and wait for auto-save.');
                    return;
                }
                showModal('publish-modal');
            });
            
            cancelPublish.addEventListener('click', () => hideModal('publish-modal'));
            
            confirmPublish.addEventListener('click', async function() {
                const customSlug = document.getElementById('custom-slug').value.trim();
                const visibility = document.querySelector('input[name="visibility"]:checked').value;
                
                // Disable button
                confirmPublish.disabled = true;
                confirmPublish.textContent = 'Publishing...';
                
                try {
                    const response = await fetch(`/hidden-admin/forms/${formData.id}/publish`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            slug: customSlug || null,
                            visibility: visibility
                        })
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        hideModal('publish-modal');
                        
                        // Store form URL and slug
                        formData.url = window.location.origin + data.url;
                        formData.slug = data.slug;
                        formData.published = true;
                        
                        // Show success modal with URL
                        document.getElementById('form-url').value = formData.url;
                        showModal('success-modal');
                        
                        // Update publish button
                        publishBtn.textContent = 'Published ✓';
                        publishBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        publishBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                        
                        // Show settings button
                        const settingsBtn = document.getElementById('settings-btn');
                        settingsBtn.classList.remove('hidden');
                        settingsBtn.addEventListener('click', function() {
                            showModal('publish-modal');
                            // Pre-fill with current values
                            document.getElementById('custom-slug').value = formData.slug || '';
                        });
                    } else {
                        showError(data.message || 'Failed to publish form. Please try again.');
                    }
                } catch (error) {
                    console.error('Publish error:', error);
                    showError('Failed to publish form. Please check your connection and try again.');
                } finally {
                    confirmPublish.disabled = false;
                    confirmPublish.textContent = 'Publish Form';
                }
            });
            
            // Success modal handlers
            document.getElementById('close-success').addEventListener('click', () => hideModal('success-modal'));
            
            document.getElementById('view-form').addEventListener('click', function() {
                const formUrl = document.getElementById('form-url').value;
                window.open(formUrl, '_blank');
            });
            
            document.getElementById('copy-url').addEventListener('click', function() {
                const urlInput = document.getElementById('form-url');
                urlInput.select();
                document.execCommand('copy');
                this.textContent = 'Copied!';
                setTimeout(() => this.textContent = 'Copy', 2000);
            });
            
            // Error toast close
            document.getElementById('close-error-toast').addEventListener('click', function() {
                const errorToast = document.getElementById('error-toast');
                errorToast.classList.add('translate-x-full');
                setTimeout(() => errorToast.classList.add('hidden'), 300);
            });
        });
    </script>
</body>
</html>