<x-layout>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="flex items-start justify-between gap-6">
            <div>
                <h2 class="text-2xl font-bold">{{ $student->name }} {{ $student->surname }}</h2>
                <p class="text-sm text-gray-500">Telefon: {{ $student->tel ?? '—' }}</p>
                <p class="text-sm text-gray-500">Stawka: {{ $student->rate }} PLN</p>
                <p class="text-sm text-gray-500">Aktywny: {{ $student->active ? 'Tak' : 'Nie' }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a class="btn" href="{{ route('dashboard.edit', $student->id) }}">Edytuj</a>
                <a class="btn" href="{{ route('dashboard.index') }}">Powrót</a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold mb-2">Dodaj lekcję</h3>
            <form action="{{ route('lessons.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <input name="title" type="text" class="border p-2 w-full" placeholder="Opcjonalny tytuł">
                <div>
                    <label class="text-sm text-gray-600">Start</label>
                    <input name="start" type="datetime-local" class="border p-2 w-full" required>
                </div>
                <div>
                    <label class="text-sm text-gray-600">End</label>
                    <input name="end" type="datetime-local" class="border p-2 w-full">
                </div>
                <textarea name="notes" class="border p-2 w-full" placeholder="Notatka (opcjonalna)"></textarea>
                <button type="submit" class="btn w-full">Dodaj lekcję</button>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold mb-2">Nadchodzące / ostatnie lekcje</h3>
            <ul class="space-y-2">
                @forelse($lessons as $lesson)
                    <li class="p-3 border rounded">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <div class="font-medium">{{ $lesson->title ?? 'Lekcja' }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $lesson->start_formatted }}
                                    @if($lesson->end) — {{ \Carbon\Carbon::parse($lesson->end_formatted)->format('H:i') }} @endif
                                </div>
                            </div>

                            <div class="text-right space-y-2">
                                @php $p = $lesson->payment; @endphp
                                @if($p)
                                    <div class="text-sm">
                                        Status: <strong>{{ $p->status }}</strong><br>
                                        Kwota: <strong>{{ number_format($p->amount,2) }} PLN</strong>
                                        @if($p->paid_at)<div class="text-xs text-gray-500">Zapłacono: {{ $p->paid_at_formatted }}</div>@endif
                                    </div>

                                    <form action="{{ route('payments.updateStatus', $p->id) }}" method="POST" class="inline-flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="border p-1">
                                            <option value="awaiting" @selected($p->status=='awaiting')>Oczekuje</option>
                                            <option value="paid" @selected($p->status=='paid')>Zapłacone</option>
                                            <option value="overdue" @selected($p->status=='overdue')>Zaległe</option>
                                        </select>
                                        <button class="btn">Zmień</button>
                                    </form>

                                    <form action="{{ route('payments.markPaid', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="btn">Oznacz zapłacone</button>
                                    </form>
                                @else
                                    <form action="{{ route('payments.store') }}" method="POST" class="space-y-2 mt-2">
                                        @csrf
                                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                        <input name="amount" type="number" step="0.01" placeholder="Kwota (PLN)" class="border p-2 w-32" required>
                                        <input name="notes" type="text" placeholder="Notatka" class="border p-2 w-full">
                                        <input type="hidden" name="status" value="awaiting">
                                        <button class="btn">Dodaj płatność</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">Brak lekcji.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <form action="{{ route('dashboard.delete', $student->id) }}" method="POST" class="mt-6">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn bg-red-600 hover:bg-red-700 text-white">Usuń ucznia</button>
    </form>
</x-layout>