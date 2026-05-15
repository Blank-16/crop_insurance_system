<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:flex flex-col shadow-lg">
                <div class="h-16 flex items-center px-6 bg-gray-800 font-bold text-xl tracking-wider">
                    CropInsure
                </div>
                <div class="flex-1 overflow-y-auto py-4">
                    <nav class="space-y-1 px-3">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} transition">
                            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>

                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.users') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('admin.users') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} mt-2 transition">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                Manage Users
                            </a>
                        @endif

                        @if (Auth::user()->role === 'proposer')
                            <a href="{{ route('proposer.plans.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('proposer.plans.*') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} mt-2 transition">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                My Plans
                            </a>
                            <a href="{{ route('proposer.policies.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('proposer.policies.*') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} mt-2 transition">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                Review Policies
                            </a>
                            <a href="{{ route('proposer.claims.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('proposer.claims.*') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} mt-2 transition">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                Manage Claims
                            </a>
                        @endif

                        @if (Auth::user()->role === 'farmer')
                            <a href="{{ route('farmer.plans') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('farmer.plans') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} mt-2 transition">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                Browse Plans
                            </a>
                            <a href="{{ route('farmer.policies') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('farmer.policies') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} mt-2 transition">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                                My Policies
                            </a>
                            <a href="{{ route('farmer.claims.index') }}" class="flex items-center px-3 py-2.5 rounded-lg {{ request()->routeIs('farmer.claims.*') ? 'bg-indigo-600' : 'hover:bg-gray-800 text-gray-300' }} mt-2 transition">
                                <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                My Claims
                            </a>
                        @endif
                    </nav>
                </div>
            </aside>

            <!-- Main Content area -->
            <div class="flex-1 flex flex-col h-screen overflow-hidden">
                <!-- Top Navbar -->
                <header class="h-16 flex-shrink-0 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
                    <div class="flex items-center">
                        <!-- Mobile menu button -->
                        <button class="md:hidden text-gray-500 hover:text-gray-700 bg-white mr-4">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        @isset($header)
                            <div class="text-gray-800">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    
                    <div class="flex items-center gap-4">
                        @php
                            $notifications = \App\Models\Notification::where('user_id', Auth::id())->latest()->take(10)->get();
                            $unreadCount = $notifications->where('is_read', false)->count();
                        @endphp
                        
                        <!-- Notification Dropdown -->
                        <div x-data="{ open: false }" class="relative mr-2">
                            <button @click="open = !open" class="focus:outline-none relative mt-1">
                                <svg class="w-6 h-6 text-gray-500 hover:text-indigo-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                @if($unreadCount > 0)
                                    <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full">{{ $unreadCount }}</span>
                                @endif
                            </button>

                            <!-- Dropdown Panel -->
                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-xl overflow-hidden z-20 border border-gray-100" style="display: none;">
                                <div class="bg-gray-50 px-4 py-2 border-b flex justify-between items-center text-sm font-semibold text-gray-700">
                                    <span>Notifications</span>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse($notifications as $notification)
                                        <div class="px-4 py-3 border-b hover:bg-gray-50 {{ !$notification->is_read ? 'bg-indigo-50 border-l-4 border-indigo-500' : '' }} transition">
                                            <p class="text-sm text-gray-800">{{ $notification->message }}</p>
                                            <div class="flex justify-between items-center mt-2">
                                                <span class="text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                                @if(!$notification->is_read)
                                                    <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                                        @csrf
                                                        <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium font-semibold">Mark Read</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="px-4 py-6 text-center text-gray-500 text-sm">
                                            No notifications.
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <span class="text-gray-700 font-medium hidden sm:inline-block">Welcome, {{ Auth::user()->name }}</span>
                        <div class="border-l pl-4 border-gray-200 flex items-center gap-4">
                            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-indigo-600 text-sm font-medium transition cursor-pointer flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium transition cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50 relative">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
        
        <!-- Toast Notification Container -->
        <div id="toast-container" class="fixed bottom-5 right-5 z-50 flex flex-col gap-2"></div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                function showToast(message) {
                    const container = document.getElementById('toast-container');
                    const toast = document.createElement('div');
                    toast.className = 'bg-indigo-600 text-white px-6 py-4 rounded-lg shadow-xl flex items-center justify-between transform transition-all duration-300 translate-y-10 opacity-0';
                    toast.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-6 h-6 mr-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            <div>
                                <h4 class="font-bold text-sm">New Update</h4>
                                <p class="text-sm opacity-90">${message}</p>
                            </div>
                        </div>
                        <button onclick="this.parentElement.remove()" class="ml-4 text-white opacity-70 hover:opacity-100">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    `;
                    container.appendChild(toast);
                    
                    // Animate in
                    setTimeout(() => {
                        toast.classList.remove('translate-y-10', 'opacity-0');
                    }, 50);

                    // Auto remove after 5 seconds
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-y-2');
                        setTimeout(() => toast.remove(), 300);
                    }, 5000);
                }

                @auth
                    @if(auth()->user()->role === 'farmer')
                        if (window.Echo) {
                            window.Echo.private('farmer.{{ auth()->id() }}')
                                .listen('.claim.status.updated', (e) => {
                                    showToast(e.message);
                                    // Optional: increment notification counter or reload notifications
                                });
                        }
                    @endif
                @endauth
            });
        </script>
    </body>
</html>
