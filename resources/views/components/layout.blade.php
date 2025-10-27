<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student dashboard</title>
        @vite('resources/css/app.css')

</head>
<body>
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif
   <x-navbar />
    <main class="bg-gray-100 min-h-screen p-8">
        {{ $slot }}
    </main>
</body>
</html>