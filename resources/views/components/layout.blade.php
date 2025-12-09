<html lang="en">

<head>
    <meta name="robots" content="noindex, nofollow">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- added -->
    @vite('resources/css/app.css')
    <!-- FullCalendar (single global bundle). Keep only this to avoid plugin conflicts. -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.19/index.global.min.js"></script>
</head>

<body>


    <x-navbar />


    <main class="bg-gray-50 min-h-screen pl-[200px]">
        <div class="container mx-auto">
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 mb-4 rounded-xl container w-max fixed bottom-0 right-4">
                    {{ session('success') }}
                </div>
            @endif
            {{ $slot }}
        </div>
    </main>


</body>

</html>
