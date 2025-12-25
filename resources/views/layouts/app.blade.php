<!DOCTYPE html>
<html lang="en" @if($scrollSmooth ?? true) class="scroll-smooth" @endif>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Alamanda Houseboat' }}</title>

    <!-- Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN for standalone usage) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Iconify (Lucide Icons) -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        /* Custom scrollbar for webkit */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e4e4e7; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #d4d4d8; }
    </style>
    @stack('styles')
</head>
<body class="@if($transparentBg ?? false) bg-white @else bg-zinc-50 @endif text-zinc-900 selection:bg-zinc-900 selection:text-white antialiased">

    <!-- Header -->
    @include('components.header', $header ?? [])

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    @include('components.footer')

    @stack('scripts')
</body>
</html>
