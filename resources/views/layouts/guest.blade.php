<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ old('theme', request()->cookie('theme', 'dark')) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' | Recruivo' : 'Recruivo' }}</title>
    
    <meta name="description" content="Recruivo connects candidates with modern teams and transparent hiring practices.">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 antialiased transition-colors duration-300 dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen flex flex-col">
        <div class="relative isolate overflow-hidden flex-1">
            <div class="pointer-events-none absolute inset-x-0 top-0 -z-10">
                <div class="mx-auto h-72 max-w-5xl rounded-full bg-gradient-to-r from-indigo-400/30 via-sky-400/20 to-purple-400/20 blur-3xl"></div>
                <div class="absolute -bottom-20 right-10 h-48 w-48 rounded-full bg-sky-300/30 blur-2xl dark:bg-sky-500/20"></div>
            </div>
            
            @include('partials.header')
            
            <main class="mx-auto max-w-6xl px-4 pb-20 pt-10 sm:px-6 sm:pb-16">
                @yield('content')
            </main>
        </div>
        
        @include('partials.footer')
    </div>
    
    @stack('scripts')
</body>
</html>

