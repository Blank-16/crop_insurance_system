<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CropInsure | Securing Your Harvest</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Tailwind Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .bg-hero {
                background-image: linear-gradient(rgba(17, 24, 39, 0.7), rgba(17, 24, 39, 0.8)), url('https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=2070&auto=format&fit=crop');
                background-size: cover;
                background-position: center;
            }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-900">
        
        <!-- Navbar -->
        <nav class="fixed w-full z-50 transition duration-300 bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="font-bold text-2xl tracking-tighter text-indigo-600">Crop<span class="text-green-500">Insure</span></span>
                    </div>
                    <div>
                        @if (Route::has('login'))
                            <div class="flex items-center gap-4">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="font-medium text-gray-600 hover:text-indigo-600 transition">Log in</a>

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition shadow-sm">Get Started</a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative bg-hero h-screen flex items-center">
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-20 flex flex-col items-center">
                <span class="inline-block py-1.5 px-3 rounded-full bg-green-500/20 text-green-300 text-sm font-semibold mb-6 border border-green-500/30 backdrop-blur-sm">Empowering Agricultural Futures</span>
                <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl max-w-4xl leading-tight">
                    Protect your harvest with <span class="text-green-400">intelligent insurance</span>
                </h1>
                <p class="mt-6 max-w-2xl text-xl text-gray-300">
                    A dedicated platform connecting hardworking farmers with reliable insurance proposers. Streamlined policies, completely hassle-free claims, total peace of mind.
                </p>
                <div class="mt-10 flex gap-4 sm:gap-6 w-full justify-center">
                    <a href="{{ route('register') }}" class="px-8 py-3.5 border border-transparent text-base font-medium rounded-full text-white bg-indigo-600 hover:bg-indigo-700 md:text-lg transition shadow-lg hover:shadow-indigo-500/25">
                        Register as Farmer
                    </a>
                    <a href="{{ route('register') }}" class="px-8 py-3.5 border border-transparent text-base font-medium rounded-full text-indigo-700 bg-white hover:bg-gray-50 md:text-lg transition shadow-lg">
                        Become a Partner
                    </a>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-24 bg-white relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">Why Choose Us</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        A better way to protect agriculture
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <!-- Feature 1 -->
                    <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                        <div class="h-12 w-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-6">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Simple Applications</h3>
                        <p class="text-gray-600">Browse customized plans based on your region and crop type, and apply with just one click.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                        <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center mb-6">
                            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Rapid Claims</h3>
                        <p class="text-gray-600">File a claim instantly with documentation. Get lightning fast reviews from insurance proposers.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-gray-50 rounded-2xl p-8 hover:shadow-xl transition duration-300 border border-gray-100">
                        <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center mb-6">
                            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Transparent Dashboards</h3>
                        <p class="text-gray-600">Track all your policies, statuses, and performance at a glance with beautiful statistical dashboards.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-900">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center md:flex-row flex-col">
                    <div class="flex opacity-80 mb-4 md:mb-0 items-center">
                        <span class="font-bold text-xl tracking-tighter text-indigo-400">Crop<span class="text-green-400">Insure</span></span>
                    </div>
                    <p class="text-gray-400 text-sm text-center md:text-right">
                        &copy; {{ date('Y') }} CropInsure. All rights reserved. <br> Made for the agricultural community.
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
