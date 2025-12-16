<html lang="en">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        // CRITICAL: Load theme BEFORE any render to prevent flash
        (function() {
            const savedTheme = localStorage.getItem('theme') || 
                              (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
            if (savedTheme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
    <script>
        // Theme Switcher - interactive part
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
            const themeLight = document.getElementById('themeLight');
            const themeDark = document.getElementById('themeDark');
            
            function setTheme(theme) {
                if (theme === 'dark') {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            }
            
            if (themeLight) {
                themeLight.addEventListener('click', () => setTheme('light'));
            }
            if (themeDark) {
                themeDark.addEventListener('click', () => setTheme('dark'));
            }
        });
    </script>
</head>

<body>
    @if (Auth::check())
        <x-navbar />
        <main class="bg-gray-50 min-h-screen pl-[200px]">
            <div class=" mx-auto">
                @if (session('success'))
                    <div class="bg-green-500 text-white p-4 mb-4 rounded-xl container w-max fixed bottom-0 right-4">
                        {{ session('success') }}
                    </div>
                @endif
                {{ $slot }}
            </div>
        </main>
    @else
        <main class="bg-gray-50 min-h-screen">
            <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
                <!-- Lewo -->
                <div class="hidden lg:block relative">
                    <img src="{{ asset('images/login.jpg') }}" alt=""
                        class="absolute inset-0 h-full w-full object-cover" />
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    <div class="absolute bottom-6 left-6 text-white">
                        <h2 class="text-3xl font-bold">TutorApp</h2>
                        <p class="text-white/80 mt-2">Zaloguj się, aby zarządzać zajęciami.</p>
                    </div>
                </div>
                <!-- Prawo-->
                {{ $slot }}
            </div>
        </main>
    @endif
</body>

</html>
