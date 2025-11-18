<x-layout>
    <div class="header">
        <h1 class="text-2xl font-bold mb-4">Lista wszystkich uczniów</h1>
        <a href="{{ route('dashboard.create') }}" class="btn text-gray-700 hover:text-red-600">Nowy uczeń</a>
    </div>
    <div class="md:col-span-1">
        <ul class="space-y-3 max-h-[60vh] overflow-auto pr-2">
            @foreach ($students as $student)
                <li>
                    <x-card href="{{ route('dashboard.show', $student->id) }}">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $student->name }} {{ $student->surname }}</h3>
                            <p class="text-sm text-gray-500">{{ $student->tel }}</p>
                        </div>
                        <div class="text-sm text-gray-600">{{ $student->rate }} PLN</div>
                    </x-card>
                </li>
            @endforeach
        </ul>

        <div class="mt-4">
            {{ $students->onEachSide(5)->links() }}
        </div>
    </div>
</x-layout>
