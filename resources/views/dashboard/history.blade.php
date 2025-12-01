<x-layout>
    <div class="max-w-6xl mx-auto mt-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Historia lekcji</h1>
            <a href="{{ route('finance.index') }}"
                class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">Przejdź do finansów</a>
        </div>

        @php
            // group lessons by month key "YYYY-MM" — fully qualify Carbon to avoid using 'use' inside Blade
            $grouped = $lessons->groupBy(function ($l) {
                return \Carbon\Carbon::parse($l->start)->format('Y-m');
            });
        @endphp

        @forelse($grouped as $month => $items)
            @php
                $monthLabel = \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y');
                $totalLessons = $items->count();
                $totalMinutes = $items->reduce(function ($carry, $l) {
                    if ($l->end) {
                        $s = \Carbon\Carbon::parse($l->start);
                        $e = \Carbon\Carbon::parse($l->end);
                        return $carry + $e->diffInMinutes($s);
                    }
                    return $carry;
                }, 0);
                $hours = intdiv($totalMinutes, 60);
                $minutes = $totalMinutes % 60;
                $totalMoney = $items->reduce(function ($carry, $l) {
                    return $carry + ($l->payment ? $l->payment->amount : 0);
                }, 0);
                $statusCounts = $items
                    ->pluck('payment')
                    ->filter()
                    ->groupBy(function ($p) {
                        return $p->status;
                    })
                    ->map->count();
            @endphp

            <div id="month-{{ $month }}" class="bg-white rounded-lg shadow p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-lg">{{ $monthLabel }}</div>
                        <div class="text-sm text-gray-500">{{ $totalLessons }} lekcji — {{ $hours }}h
                            {{ $minutes }}m — {{ number_format($totalMoney, 2) }} PLN</div>
                    </div>

                    <div class="text-sm space-x-2">
                        @foreach (['awaiting', 'paid', 'overdue'] as $st)
                            <a href="{{ route('finance.index') }}?status={{ $st }}#month-{{ $month }}"
                                class="inline-block px-3 py-1 rounded {{ $statusCounts[$st] ?? 0 ? 'bg-gray-100 text-gray-800' : 'bg-gray-50 text-gray-400' }}">
                                {{ ucfirst($st) }}: {{ $statusCounts[$st] ?? 0 }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    <ul class="divide-y">
                        @foreach ($items as $lesson)
                            @php $p = $lesson->payment; @endphp
                            <li class="py-3 flex justify-between items-start">
                                <div>
                                    <div class="font-medium text-gray-800">{{ $lesson->title ?? 'Lekcja' }}</div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ \Carbon\Carbon::parse($lesson->start)->format('Y-m-d H:i') }}
                                        @if ($lesson->end)
                                            — {{ \Carbon\Carbon::parse($lesson->end)->format('H:i') }}
                                            ({{ \Carbon\Carbon::parse($lesson->end)->diffForHumans(\Carbon\Carbon::parse($lesson->start), true) }})
                                        @endif
                                    </div>
                                </div>

                                <div class="text-right text-sm">
                                    @if ($p)
                                        <div class="font-medium">{{ number_format($p->amount, 2) }} PLN</div>
                                        <div class="text-xs text-gray-500">Status: <a
                                                href="{{ route('finance.index') }}#payment-{{ $p->id }}"
                                                class="underline">{{ $p->status }}</a></div>
                                    @else
                                        <div class="text-gray-500">Brak płatności</div>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-500">Brak historii lekcji.</div>
        @endforelse
    </div>
</x-layout>
