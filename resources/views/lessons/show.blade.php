<x-layout>
    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $lesson->title ?? 'Lekcja' }}</h1>
                    <p class="text-sm text-gray-500 mt-2">
                        Uczeń: <span class="font-medium">{{ optional($lesson->student)->name }}
                            {{ optional($lesson->student)->surname }}</span>
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('lessons.edit', $lesson->id) }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Edytuj
                    </a>
                    <a href="{{ route('dashboard.show', $lesson->student_id) }}"
                        class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200">
                        Powrót
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="border rounded p-4">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Start</h3>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $lesson->start ? $lesson->start->format('d.m.Y H:i') : '—' }}
                    </p>
                </div>
                <div class="border rounded p-4">
                    <h3 class="text-sm font-semibold text-gray-600 mb-2">Koniec</h3>
                    <p class="text-lg font-medium text-gray-800">
                        {{ $lesson->end ? $lesson->end->format('d.m.Y H:i') : '—' }}
                    </p>
                </div>
            </div>

            @if ($lesson->notes)
                <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">Notatka</h3>
                    <p class="text-gray-700">{{ $lesson->notes }}</p>
                </div>
            @endif

            @php $payment = $lesson->payment; @endphp
            @if ($payment)
                <div class="border rounded p-4 mb-6">
                    <h3 class="text-sm font-semibold text-gray-600 mb-3">Płatność</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Kwota:</span>
                            <span class="font-medium">{{ number_format($payment->amount, 2, '.', '') }} PLN</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span
                                class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                @if ($payment->status === 'paid') bg-green-100 text-green-700
                                @elseif ($payment->status === 'overdue')
                                    bg-red-100 text-red-700
                                @else
                                    bg-yellow-100 text-yellow-700 @endif
                            ">
                                @switch ($payment->status)
                                    @case ('paid')
                                        Zapłacone
                                    @break

                                    @case ('overdue')
                                        Zaległe
                                    @break

                                    @default
                                        Oczekuje
                                @endswitch
                            </span>
                        </div>
                        @if ($payment->paid_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Zapłacono:</span>
                                <span class="font-medium">{{ $payment->paid_at->format('d.m.Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded p-4 mb-6">
                    <p class="text-sm text-gray-600">Brak powiązanej płatności.</p>
                </div>
            @endif

            <div class="flex gap-2 pt-4 border-t">
                <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST"
                    onsubmit="return confirm('Czy na pewno chcesz usunąć tę lekcję?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Usuń lekcję
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
