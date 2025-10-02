<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>All Forms - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-white shadow-sm border-b sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900">All Forms</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">{{ $adminUsername }}</span>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Header with Search and Create Button -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Manage Forms</h2>
                <p class="text-sm text-gray-600 mt-1">View, edit, and manage all your forms</p>
            </div>
            <a href="{{ route('admin.forms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Form
            </a>
        </div>

        <!-- Search Bar -->
        <div class="mb-6 bg-white p-4 rounded-lg shadow-sm">
            <form method="GET" action="{{ route('admin.forms.index') }}" class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Search forms by title or description..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                    Search
                </button>
                @if($search)
                    <a href="{{ route('admin.forms.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        <!-- Forms Table -->
        @if($forms->count() > 0)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Form</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submissions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($forms as $form)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-{{ $form->color }}-100 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-{{ $form->color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $form->title }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($form->description, 50) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($form->status === 'published')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Draft
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium">{{ $form->submissions_count }}</div>
                                    <div class="text-xs text-gray-500">responses</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $form->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.forms.analytics', $form->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded text-xs font-medium transition">
                                            View Submissions
                                        </a>
                                        <a href="{{ route('admin.forms.create') }}?edit={{ $form->id }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1.5 rounded text-xs font-medium transition">
                                            Edit
                                        </a>
                                        <button onclick="confirmDelete({{ $form->id }}, '{{ $form->title }}')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs font-medium transition">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $forms->appends(['search' => $search])->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">No forms found</h3>
                <p class="mt-2 text-sm text-gray-500">
                    @if($search)
                        No forms match your search criteria. Try different keywords.
                    @else
                        Get started by creating your first form.
                    @endif
                </p>
                <div class="mt-6">
                    @if($search)
                        <a href="{{ route('admin.forms.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg font-medium transition inline-block">
                            Clear Search
                        </a>
                    @else
                        <a href="{{ route('admin.forms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition inline-block">
                            Create Your First Form
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Delete Form?</h3>
                    <p class="text-gray-600 mb-2">Are you sure you want to delete <strong id="form-name"></strong>?</p>
                    <p class="text-sm text-red-600 font-medium mb-6">This action cannot be undone and will delete all submissions!</p>
                    
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mb-6">
                        <p class="text-sm text-yellow-800 text-left font-medium">⚠️ Final Confirmation Required</p>
                        <p class="text-xs text-yellow-700 text-left mt-1">Type "DELETE" to confirm:</p>
                        <input type="text" id="delete-confirm-input" class="w-full mt-2 px-3 py-2 border border-yellow-300 rounded text-sm" placeholder="Type DELETE">
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium">
                            Cancel
                        </button>
                        <button onclick="executeDelete()" id="confirm-delete-btn" disabled class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                            Delete Form
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteFormId = null;
        
        function confirmDelete(formId, formName) {
            deleteFormId = formId;
            document.getElementById('form-name').textContent = formName;
            document.getElementById('delete-confirm-input').value = '';
            document.getElementById('confirm-delete-btn').disabled = true;
            document.getElementById('delete-modal').classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            deleteFormId = null;
        }
        
        // Enable delete button only when "DELETE" is typed
        document.getElementById('delete-confirm-input').addEventListener('input', function() {
            const confirmBtn = document.getElementById('confirm-delete-btn');
            if (this.value === 'DELETE') {
                confirmBtn.disabled = false;
            } else {
                confirmBtn.disabled = true;
            }
        });
        
        async function executeDelete() {
            if (!deleteFormId) return;
            
            const btn = document.getElementById('confirm-delete-btn');
            btn.disabled = true;
            btn.textContent = 'Deleting...';
            
            try {
                const response = await fetch(`/hidden-admin/forms/${deleteFormId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete form'));
                    btn.disabled = false;
                    btn.textContent = 'Delete Form';
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Failed to delete form. Please try again.');
                btn.disabled = false;
                btn.textContent = 'Delete Form';
            }
        }
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeDeleteModal();
            }
        });
    </script>
</body>
</html>
