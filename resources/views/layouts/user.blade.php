<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="transition-colors duration-300">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - User</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
</head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

<body class="bg-pink-50 dark:bg-gray-800 font-sans antialiased">
    <div id="app">
        <nav class="bg-pink-600 fixed top-0 left-0 w-full z-50 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">

                    <div class="flex-shrink-0">
                        <a href="{{ url('/user/dashboard') }}" class="text-white font-bold text-lg">
                            ADR BOOKS ( {{ Auth::user()->name }} )
                        </a>
                    </div>

                    <div class="hidden md:flex items-center space-x-6">
                        <button id="dark-mode-toggle" class="text-white text-xl hover:text-pink-100 p-2 rounded-full">
                        </button>

                        <button id="lang-toggle" class="text-white hover:text-pink-100 font-medium">
                            {{ session('locale', 'id') === 'id' ? 'EN' : 'ID' }}
                        </button>

                        <a href="{{ route('about.show') }}" class="text-white font-semibold hover:text-gray-300">
                            About Us
                        </a>

                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="text-white hover:text-pink-100 font-medium">
                            Logout
                        </a>
                    </div>

                    <div class="md:hidden flex items-center space-x-2">
                        <button id="dark-mode-toggle-mobile"
                            class="text-white text-xl hover:text-pink-100 p-2 rounded-full">
                        </button>

                        <button id="mobile-menu-button" class="text-white p-2 rounded-md hover:bg-pink-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16m-7 6h7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div id="mobile-menu" class="hidden md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="{{ route('about.show') }}" class="block text-white px-3 py-2 rounded-md hover:bg-pink-700">
                        About Us
                    </a>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="block text-white px-3 py-2 rounded-md hover:bg-pink-700">
                        Logout
                    </a>
                    <button id="lang-toggle-mobile"
                        class="block w-full text-left text-white px-3 py-2 rounded-md hover:bg-pink-700">
                        {{ session('locale', 'id') === 'id' ? 'EN' : 'ID' }}
                    </button>
                </div>
            </div>
        </nav>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <main class="pt-20">
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
        // --- Dark Mode Toggle ---
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        const darkModeToggleMobile = document.getElementById('dark-mode-toggle-mobile');
        const html = document.documentElement;
        const moonIcon = '🌙';
        const sunIcon = '☀️';

        // Fungsi untuk mengatur tema dan ikon
        function setDarkMode(isDark) {
            if (isDark) {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                if (darkModeToggle) darkModeToggle.innerHTML = sunIcon;
                if (darkModeToggleMobile) darkModeToggleMobile.innerHTML = sunIcon;
            } else {
                html.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                if (darkModeToggle) darkModeToggle.innerHTML = moonIcon;
                if (darkModeToggleMobile) darkModeToggleMobile.innerHTML = moonIcon;
            }
        }

        // Cek tema saat halaman dimuat
        const currentTheme = localStorage.getItem('theme') || 'light';
        setDarkMode(currentTheme === 'dark');

        // Tambah event listener HANYA JIKA tombolnya ada
        if (darkModeToggle) {
            darkModeToggle.addEventListener('click', () => {
                setDarkMode(!html.classList.contains('dark'));
            });
        }
        if (darkModeToggleMobile) {
            darkModeToggleMobile.addEventListener('click', () => {
                setDarkMode(!html.classList.contains('dark'));
            });
        }

        // --- Mobile Menu Toggle ---
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        // Tambah event listener HANYA JIKA kedua elemen ada
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // --- Language Toggle ---
        const langToggle = document.getElementById('lang-toggle');
        const langToggleMobile = document.getElementById('lang-toggle-mobile');

        function toggleLanguage() {
            const currentLocale = '{{ session('locale', 'id') }}';
            const newLocale = currentLocale === 'id' ? 'en' : 'id';

            // Create form to submit language change
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/user/language';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const localeInput = document.createElement('input');
            localeInput.type = 'hidden';
            localeInput.name = 'locale';
            localeInput.value = newLocale;

            form.appendChild(csrfToken);
            form.appendChild(localeInput);
            document.body.appendChild(form);
            form.submit();
        }

        if (langToggle) {
            langToggle.addEventListener('click', toggleLanguage);
        }
        if (langToggleMobile) {
            langToggleMobile.addEventListener('click', toggleLanguage);
        }

        // Initialize AOS
        AOS.init();
    </script>
</body>

</html>