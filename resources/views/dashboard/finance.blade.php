<x-layout>
    <div class="h-16 p-4 bg-white border-b border-gray-200 flex items-center justify-between">
        <h1>Finanse</h1>
        <a href="{{ route('history.index') }}"
            class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Przejdź do historii</a>
    </div>

    <div class="p-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Suma otrzymana</div>
                <div class="text-2xl font-bold text-green-600">{{ number_format($totalPaid,2) }} PLN</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
                <div class="text-sm text-gray-500 mb-1">Oczekujące płatności</div>
                <div class="text-2xl font-bold text-yellow-600">{{ number_format($totalAwaiting,2) }} PLN</div>
            </div>  
        </div>

        <!-- Tabela -->
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Data</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Uczestnik</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Lekcja</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Kwota</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-800">Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $p)
                        @php
                            $statusClass = match($p->status) {
                                'zapłacone' => 'bg-green-100 text-green-700',
                                'oczekuje' => 'bg-yellow-100 text-yellow-700',
                                default => 'bg-gray-100 text-gray-700'
                            };
                            $statusLabel = match($p->status) {
                                'zapłacone' => 'Zapłacone',
                                'oczekuje' => 'Oczekuje',
                                default => $p->status
                            };
                        @endphp
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $p->created_at_formatted }}</td>
                            <td class="px-6 py-4 text-gray-800">
                                <a href="{{ route('dashboard.show', $p->lesson->student->id) }}" class="hover:text-blue-600">
                                    {{ $p->lesson->student->name ?? '—' }} {{ $p->lesson->student->surname ?? '' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-gray-800">{{ $p->lesson->title ?? 'Lekcja' }}</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ number_format($p->amount,2) }} PLN</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if ($p->status === 'oczekuje')
                                    <form action="{{ route('payments.markPaid', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">Zapłacono</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-sm">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                Brak historii płatności.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
