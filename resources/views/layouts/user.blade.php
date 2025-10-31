<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - User</title>


    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

<body class="bg-pink-50 dark:bg-gray-800 font-sans antialiased">
    <div id="app">
        <!-- Navbar -->
        <nav class="bg-pink-600 fixed top-0 left-0 w-full z-50 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ url('/user/dashboard') }}" class="text-white font-bold text-lg">
                            ADR BOOKS ( {{ Auth::user()->name }} )
                        </a>
                    </div>


                    <div class="flex items-center space-x-6">
                        <!-- Dark Mode Toggle -->
                        <button id="dark-mode-toggle" class="text-white hover:text-pink-100 font-medium">
                            🌙 Dark Mode
                        </button>

                        <!-- Language Toggle -->
                        <button id="lang-toggle" class="text-white hover:text-pink-100 font-medium">
                            EN/ID
                        </button>

                        <!-- Tombol Logout -->
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="text-white hover:text-pink-100 font-medium">
                            Logout
                        </a>
                        <a href="{{ route('about.show') }}" class="text-white font-semibold hover:text-gray-300">
                            About Us
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <!-- Content -->
        <main class="pt-20">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const html = document.documentElement;

        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            html.classList.add('dark');
            darkModeToggle.textContent = '☀️ Light Mode';
        } else {
            darkModeToggle.textContent = '🌙 Dark Mode';
        }

        darkModeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            const theme = html.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
            darkModeToggle.textContent = theme === 'dark' ? '☀️ Light Mode' : '🌙 Dark Mode';
        });

        // Language Toggle (Basic implementation - would need full Laravel localization)
        const langToggle = document.getElementById('lang-toggle');
        let currentLang = localStorage.getItem('lang') || 'id';
        langToggle.textContent = currentLang === 'id' ? 'EN' : 'ID';

        langToggle.addEventListener('click', () => {
            currentLang = currentLang === 'id' ? 'en' : 'id';
            localStorage.setItem('lang', currentLang);
            langToggle.textContent = currentLang === 'id' ? 'EN' : 'ID';
            // In a real implementation, this would reload the page or update content dynamically
            alert('Language switched to ' + (currentLang === 'id' ? 'Bahasa Indonesia' : 'English') + '. Reload page to apply.');
        });

        // Initialize AOS
        AOS.init();
    </script>
</body>

</html>