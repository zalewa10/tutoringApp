<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- added -->
    @vite('resources/css/app.css')
    <!-- FullCalendar (single global bundle). Keep only this to avoid plugin conflicts. -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
</head>
<body>
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 mb-4 rounded container mx-auto mt-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- nicer header / navbar -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-6 py-4 flex items-center gap-6">
            <a href="{{ route('dashboard.index') }}" class="text-2xl font-extrabold text-red-600">Tutoring</a>

            <nav class="ml-6 flex items-center gap-4 text-sm">
                @auth
                    <a href="{{ route('dashboard.index') }}" class="text-gray-700 hover:text-red-600">Kalendarz</a>
                    <a href="{{ route('students.index') }}" class="text-gray-700 hover:text-red-600">Uczniowie</a>
                    <a href="{{ route('finance.index') }}" class="text-gray-700 hover:text-red-600">Finanse</a>
                @else
                    <a href="{{ route('show.login') }}" class="text-gray-700 hover:text-red-600">Login</a>
                    <a href="{{ route('show.register') }}" class="text-gray-700 hover:text-red-600">Register</a>
                @endauth
            </nav>

            <div class="ml-auto flex items-center gap-4">
                @auth
                    <span class="text-sm text-gray-600">Witaj, {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button class="btn px-3 py-1">Wyloguj</button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="bg-gray-50 min-h-screen py-8">
        <div class="container mx-auto px-6">
            {{ $slot }}
        </div>
    </main>

    
</body>
</html>