<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LIMS Pro') }} - {{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased h-full bg-slate-50 text-slate-800">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 bg-slate-900 text-white flex-shrink-0 flex flex-col">
            <!-- Brand Logo / Name -->
            <div class="p-5 flex items-center justify-between border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-500 to-cyan-400 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
                        <i class="fa-solid fa-microscope"></i>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg leading-tight tracking-wide text-white">MediLab LIMS</h1>
                        <span class="text-xs text-indigo-400 font-medium">
                            @if(auth()->user()->role === 'owner')
                                SaaS Control Center
                            @else
                                {{ auth()->user()->company->name ?? 'Lab System' }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto">
                @if(auth()->user()->role === 'owner')
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">SaaS Management</p>
                    
                    <a href="{{ route('owner.dashboard') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('owner.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                        <span>SaaS Overview</span>
                    </a>

                    <a href="{{ route('owner.companies.index') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('owner.companies.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-building flex-shrink-0 w-5 text-center text-base"></i>
                        <span>Companies Management</span>
                    </a>
                @elseif(auth()->user()->role === 'super_admin')
                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Main Menu</p>
                    
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center text-base"></i>
                        <span>Dashboard</span>
                    </a>

                    <p class="px-3 text-xs font-semibold text-slate-400 uppercase tracking-wider mt-6 mb-2">Lab Operations</p>

                    <a href="#" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors opacity-75 cursor-not-allowed" title="Module 1 - Patient Booking">
                        <i class="fa-solid fa-user-plus w-5 text-center"></i>
                        <span>Patient Registration</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors opacity-75 cursor-not-allowed" title="Module 2 - Sample Collection & Barcodes">
                        <i class="fa-solid fa-vial w-5 text-center"></i>
                        <span>Sample Collection</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors opacity-75 cursor-not-allowed" title="Module 3 - Dynamic Test Result Entry">
                        <i class="fa-solid fa-file-waveform w-5 text-center"></i>
                        <span>Test Result Entry</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 px-3.5 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors opacity-75 cursor-not-allowed" title="Module 4 - Pathologist Review">
                        <i class="fa-solid fa-user-doctor w-5 text-center"></i>
                        <span>Pathologist Review</span>
                    </a>
                @endif
            </nav>

            <!-- User Info Footer -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-full bg-slate-700 flex items-center justify-center font-bold text-white text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-sm font-medium text-slate-200 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 capitalize truncate">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-red-400 rounded-lg hover:bg-slate-800 transition-colors" title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content Body -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Navigation Header -->
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-4">
                    <h2 class="text-xl font-bold text-slate-800">{{ $header ?? 'Dashboard' }}</h2>
                </div>

                <div class="flex items-center space-x-4">
                    @if(auth()->user()->role === 'owner')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                            <i class="fa-solid fa-crown mr-1.5 text-purple-600"></i> SaaS Owner
                        </span>
                    @elseif(auth()->user()->company)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <i class="fa-solid fa-building-circle-check mr-1.5 text-emerald-600"></i> {{ auth()->user()->company->name }}
                        </span>
                    @endif
                </div>
            </header>

            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 bg-slate-50">
                <!-- Flash Success Notification -->
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
                            <span class="font-medium text-sm">{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                <!-- Flash Error Notification -->
                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between shadow-sm">
                        <div class="flex items-center space-x-3">
                            <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
                            <span class="font-medium text-sm">{{ session('error') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
