<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Locked</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-lg w-full">
        <div class="bg-white rounded-xl shadow-2xl p-10 text-center border-t-4 border-red-500">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-red-100 to-red-200 mb-6 shadow-lg">
                <svg class="h-10 w-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Submissions Locked</h2>
            <p class="text-gray-600 mb-6 text-lg leading-relaxed">
                This form is set to <span class="font-semibold text-red-600">private</span> and is not currently accepting submissions from the public.
            </p>
            <div class="bg-red-50 rounded-lg p-4 mb-6 border border-red-200">
                <p class="text-sm text-red-800 flex items-start">
                    <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Only authorized users can submit responses to this form.</span>
                </p>
            </div>
            <p class="text-sm text-gray-500">
                If you believe this is an error, please contact the form administrator.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Powered by Form Creation System</p>
        </div>
    </div>
</body>
</html>
