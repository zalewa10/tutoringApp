<x-layout>
    <div class="h-14 p-4 bg-white border-b border-gray-200 flex items-center justify-between">
        <h1>Lista wszystkich uczniów</h1>
        <a href="{{ route('dashboard.create') }}" class="px-3 py-2 btn btn-primary">Nowy
            uczeń</a>
    </div>
    <div class="p-4">
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Imię i Nazwisko</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Stawka</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-800">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-800">{{ $student->name }} {{ $student->surname }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-block bg-gray-100 text-gray-800 px-2 py-1 rounded text-sm">{{ $student->rate }}
                                    PLN/h</span>
                            </td>
                            <td> <span
                                    class="inline-block ml-2 px-3 py-1 rounded-full text-xs font-medium {{ $student->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $student->active ? 'Aktywny' : 'Nieaktywny' }}
                                </span></td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('dashboard.show', $student->id) }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">Szczegóły</a>
                            </td>
                        </tr>
                    @endforeach
                    @if ($students->hasPages())
                        <tr>
                            <td colspan="4" class="p-6 py-2">
                                {{ $students->onEachSide(1)->links() }}
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
