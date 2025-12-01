<x-layout>
    <div class="max-w-6xl mx-auto mt-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Lista wszystkich uczniów</h1>
            <a href="{{ route('dashboard.create') }}"
                class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Nowy uczeń</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($students as $student)
                <a href="{{ route('dashboard.show', $student->id) }}"
                    class="block bg-white rounded-lg shadow p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">{{ $student->name }} {{ $student->surname }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">tel: {{ $student->tel ?? '—' }}</p>
                        </div>

                        <div class="text-sm">
                            <span class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded">{{ $student->rate }}
                                PLN/h</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $students->onEachSide(5)->links() }}
        </div>
    </div>
</x-layout>
