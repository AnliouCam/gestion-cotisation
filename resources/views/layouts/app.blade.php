<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestion Cotisation') }} - @yield('title', 'Accueil')</title>

    {{-- Styles Tailwind CSS via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">

    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">

        {{-- SIDEBAR MOBILE OVERLAY --}}
        <div x-show="sidebarOpen"
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden">
        </div>

        {{-- SIDEBAR --}}
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
             class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out lg:static lg:inset-0">

            <div class="flex flex-col h-full">
                {{-- Logo --}}
                <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
                    <h1 class="text-lg font-bold text-blue-600">
                        💰 {{ config('app.name', 'Gestion Cotisation') }}
                    </h1>
                    <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Navigation Menu --}}
                @auth
                <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Tableau de bord
                    </a>

                    {{-- Section Consultation (visible pour tous) --}}
                    <div class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            {{ Auth::user()->role === 'admin' ? 'Gestion' : 'Consultation' }}
                        </p>
                    </div>

                    @if(Auth::user()->role === 'admin')
                        {{-- Utilisateurs (Admin uniquement) --}}
                        <a href="{{ route('utilisateurs.index') }}"
                           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('utilisateurs.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            Membres
                        </a>
                    @endif

                    {{-- Événements (tous) --}}
                    <a href="{{ route('evenements.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('evenements.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Événements
                    </a>

                    {{-- Cotisations (tous) --}}
                    <a href="{{ route('cotisations.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('cotisations.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Cotisations
                    </a>

                    {{-- Dépenses (tous) --}}
                    <a href="{{ route('depenses.index') }}"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('depenses.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Dépenses
                    </a>

                    @if(Auth::user()->role === 'admin')
                        {{-- Catégories (Admin uniquement) --}}
                        <a href="{{ route('categories.index') }}"
                           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('categories.*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-gray-50' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Catégories
                        </a>
                    @endif

                    {{-- Section Rapports (visible pour tous) --}}
                    <div class="pt-4">
                        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Rapports
                        </p>
                    </div>

                    <a href="#"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Statistiques
                    </a>

                    <a href="#"
                       class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Historique
                    </a>
                </nav>

                {{-- User Profile --}}
                <div class="border-t border-gray-200 p-4">
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center w-full px-4 py-2 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">
                            <div class="flex items-center flex-1">
                                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}
                                </div>
                                <div class="ml-3 text-left">
                                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->nom }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ Auth::user()->role === 'admin' ? 'Administrateur' : 'Membre' }}
                                    </p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Dropdown --}}
                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute bottom-full left-0 right-0 mb-2 bg-white rounded-lg shadow-lg border border-gray-200 py-1"
                             style="display: none;">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Mon profil
                            </a>
                            <a href="{{ route('changer-mot-de-passe') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                Changer mot de passe
                            </a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- TOP NAVBAR --}}
            <header class="bg-white border-b border-gray-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    {{-- Mobile menu button --}}
                    <button @click="sidebarOpen = true"
                            class="lg:hidden text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    {{-- Page Title --}}
                    <div class="flex-1 lg:ml-0 ml-4">
                        <h2 class="text-xl font-semibold text-gray-800">
                            @yield('page-title', 'Tableau de bord')
                        </h2>
                    </div>

                    {{-- Right side icons (notifications, etc.) --}}
                    <div class="flex items-center space-x-4">
                        {{-- Notification badge (placeholder) --}}
                        <button class="relative text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-0 right-0 block w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        {{-- User info (desktop only) --}}
                        @auth
                        <div class="hidden lg:flex items-center">
                            <span class="text-sm text-gray-700">{{ Auth::user()->nom }}</span>
                            <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ Auth::user()->role === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ Auth::user()->role === 'admin' ? 'Admin' : 'Membre' }}
                            </span>
                        </div>
                        @endauth
                    </div>
                </div>
            </header>

            {{-- MAIN CONTENT AREA --}}
            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 sm:p-6 lg:p-8">
                {{-- Flash Messages --}}
                @include('components.flash-messages')

                {{-- Page Content --}}
                @yield('content')
            </main>

            {{-- FOOTER --}}
            <footer class="bg-white border-t border-gray-200 py-4">
                <div class="px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-gray-500 text-sm">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Application de transparence financière.
                    </p>
                </div>
            </footer>
        </div>
    </div>

</body>
</html>
