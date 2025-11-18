<x-layout>
    <h1 class="text-2xl font-bold mb-4">Finanse</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Suma otrzymana</div>
            <div class="text-2xl font-bold text-green-600">{{ number_format($totalPaid,2) }} PLN</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Oczekujące płatności</div>
            <div class="text-2xl font-bold text-yellow-600">{{ number_format($totalAwaiting,2) }} PLN</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-sm text-gray-500">Zaległe</div>
            <div class="text-2xl font-bold text-red-600">{{ number_format($totalOverdue,2) }} PLN</div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <h3 class="text-lg font-semibold mb-2">Historia płatności</h3>
        <table class="w-full text-left">
            <thead>
                <tr class="text-sm text-gray-500">
                    <th>Data</th>
                    <th>Uczestnik</th>
                    <th>Lekcja</th>
                    <th>Kwota</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $p)
                    <tr class="border-t">
                        <td class="py-2 text-sm">{{ $p->created_at_formatted }}</td>
                        <td class="py-2 text-sm">{{ $p->lesson->student->name ?? '—' }} {{ $p->lesson->student->surname ?? '' }}</td>
                        <td class="py-2 text-sm">{{ $p->lesson->title ?? 'Lekcja' }}</td>
                        <td class="py-2 text-sm">{{ number_format($p->amount,2) }} PLN</td>
                        <td class="py-2 text-sm status status-{{ $p->status }}"><span>{{ $p->status }}</span></td>
                        <td class="py-2 text-sm">
                            <form action="{{ route('payments.markPaid', $p->id) }}" method="POST" class="inline">
                                @csrf
                                <button class="btn">Mark Paid</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
