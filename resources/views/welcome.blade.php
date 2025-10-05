<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SITC Campus - Smart Forms System</title>
    <link rel="icon" type="image/png" href="{{ asset('images/sitc-icon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/sitc-icon.png') }}">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/sitc-logo.png') }}" alt="SITC Campus" class="h-10 w-auto">
                    <h1 class="hidden text-2xl font-bold text-black">
                        SITC Campus
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#features" class="text-gray-700 hover:text-purple-600 transition">Features</a>
                    <a href="https://sitc.lk/contact/" class="text-gray-700 hover:text-purple-600 transition">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="relative bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 text-white py-20 md:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-4xl md:text-6xl font-bold mb-6">
                    Smart Forms System
                </h2>
                <p class="text-xl md:text-2xl mb-8 text-purple-100 max-w-3xl mx-auto">
                    Create, manage, and analyze forms effortlessly. Built for SITC Campus.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="#features" class="bg-white text-purple-600 px-8 py-4 rounded-lg font-semibold hover:bg-purple-50 transition shadow-lg">
                        Explore Features
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
            </svg>
        </div>
    </section>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">Everything you need to create, manage, and analyze forms</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-purple-50 to-blue-50 p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Lightning Fast</h3>
                    <p class="text-gray-600">Built with modern technology for instant form creation and submission processing.</p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Secure & Reliable</h3>
                    <p class="text-gray-600">Your data is protected with enterprise-grade security and automated backups.</p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Fully Customizable</h3>
                    <p class="text-gray-600">Choose from multiple field types, colors, and settings to match your needs.</p>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Advanced Analytics</h3>
                    <p class="text-gray-600">Track submissions with real-time analytics, charts, and comprehensive reports.</p>
                </div>

                <div class="bg-gradient-to-br from-red-50 to-pink-50 p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-600 to-pink-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Excel Export</h3>
                    <p class="text-gray-600">Export all submissions to professionally formatted Excel files with one click.</p>
                </div>

                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-8 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Mobile Friendly</h3>
                    <p class="text-gray-600">Responsive design ensures perfect display on all devices and screen sizes.</p>
                </div>
            </div>
        </div>
    </section>

    

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4 bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">
                        SITC Campus
                    </h3>
                    <p class="text-gray-400">
                        Empowering education through technology. Smart Forms System for modern campus management.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition">Features</a></li>
                        <li><a href="https://sitc.lk" class="text-gray-400 hover:text-white transition" target="_blank" rel="noopener">SITC Official</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Connect With Us</h4>
                    <p class="text-gray-400 mb-2">info@sitc.lk</p>
                    <p class="text-gray-400 mb-2">+94 11 4 532139</p>
                    <p class="text-gray-400">L26, WTC, Colombo 01, Sri Lanka</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} SITC Campus. All rights reserved. Built with ❤️ for education by <a href="https://codezela.com" class="text-gray-400 hover:text-white transition" target="_blank">Codezela</a>.</p>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
