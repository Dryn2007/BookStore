<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Tailwind CSS -->


    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=Nunito:400,600,700" rel="stylesheet" />

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen font-sans antialiased transition-colors duration-300">
    <div id="app">
        <!-- Navbar -->
        <nav class="bg-white dark:bg-gray-800 fixed w-full z-50 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo -->
                    <a href="{{ url('/admin/dashboard') }}"
                        class="text-xl font-bold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                        <i data-lucide="book-open" class="w-6 h-6"></i>
                        ADR BOOKS
                    </a>

                    <!-- Right Section -->
                    <div class="flex items-center gap-4">
                        <!-- Dark Mode Toggle -->
                        <button onclick="toggleDarkMode()"
                            class="text-gray-600 dark:text-gray-300 hover:text-indigo-500">
                            <i data-lucide="moon" class="w-5 h-5"></i>
                        </button>

                        <!-- User Dropdown -->
                        <div class="relative">
                            <button id="dropdownButton" onclick="toggleDropdown()"
                                class="flex items-center gap-2 text-gray-700 dark:text-gray-200 font-semibold hover:text-indigo-500 focus:outline-none">
                                <i data-lucide="user" class="w-5 h-5"></i>
                                {{ Auth::user()->name }}
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div id="dropdownMenu"
                                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2 hidden">
                                <a href="{{ url('/admin/dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Dashboard
                                </a>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Logout
                                </a>

                                <a href="{{ route('admin.about.edit') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    About Us
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hidden logout form -->
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>

        <!-- Page Content -->
        <main class="pt-16">
            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();

        function toggleDropdown() {
            const menu = document.getElementById('dropdownMenu');
            menu.classList.toggle('hidden');
        }

        window.addEventListener('click', function (e) {
            const btn = document.getElementById('dropdownButton');
            const menu = document.getElementById('dropdownMenu');
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add('hidden');
            }
        });

        function toggleDarkMode() {
            document.documentElement.classList.toggle('dark');
        }
    </script>

    @stack('scripts')
</body>

</html>