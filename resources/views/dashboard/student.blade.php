<x-layout>
    <div class="max-w-4xl mx-auto mt-8 space-y-6">
        <div class="bg-white rounded-lg shadow p-6 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $student->name }} {{ $student->surname }}</h2>
                <p class="text-sm text-gray-500 mt-1">Telefon: {{ $student->tel ?? '—' }}</p>
                <p class="text-sm text-gray-500 mt-1">Stawka: <span class="font-medium text-gray-800">{{ $student->rate }}
                        PLN</span></p>
                <p class="text-sm mt-2">
                    Aktywny:
                    <span
                        class="inline-block ml-2 px-2 py-0.5 rounded-full text-xs font-medium {{ $student->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $student->active ? 'Tak' : 'Nie' }}
                    </span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700"
                    href="{{ route('dashboard.edit', $student->id) }}">Edytuj</a>
                <a class="px-3 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200"
                    href="{{ route('dashboard.index') }}">Powrót</a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Dodaj lekcję</h3>
                <form action="{{ route('lessons.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                    <div>
                        <label class="text-sm text-gray-700">Tytuł (opcjonalny)</label>
                        <input name="title" type="text"
                            class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            placeholder="Opcjonalny tytuł">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-700">Start</label>
                            <input name="start" type="datetime-local"
                                class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                required>
                        </div>
                        <div>
                            <label class="text-sm text-gray-700">Koniec</label>
                            <input name="end" type="datetime-local"
                                class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        </div>
                    </div>

                    <div>
                        <label class="text-sm text-gray-700">Notatka</label>
                        <textarea name="notes"
                            class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            placeholder="Notatka (opcjonalna)"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">Dodaj
                        lekcję</button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Nadchodzące / ostatnie lekcje</h3>
                <ul class="space-y-3">
                    @forelse($lessons as $lesson)
                        <li class="p-4 border rounded hover:shadow-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-start">
                                {{-- left: lesson details --}}
                                <div>
                                    <div class="font-medium text-gray-800">{{ $lesson->title ?? 'Lekcja' }}</div>
                                    <div class="text-sm text-gray-500 mt-1">
                                        {{ $lesson->start_formatted }}
                                        @if ($lesson->end)
                                            — {{ \Carbon\Carbon::parse($lesson->end_formatted)->format('H:i') }}
                                        @endif
                                    </div>
                                    @if ($lesson->notes)
                                        <div class="mt-2 text-sm text-gray-600">{{ $lesson->notes }}</div>
                                    @endif
                                </div>

                                {{-- right: payments/actions (fixed width on md+) --}}
                                <div class="w-full  ml-auto text-right space-y-3 flex flex-col items-end">
                                    @php $p = $lesson->payment; @endphp
                                    @if ($p)
                                        <div class="text-sm">
                                            <div>Status: <strong class="capitalize">{{ $p->status }}</strong></div>
                                            <div>Kwota: <strong>{{ number_format($p->amount, 2) }} PLN</strong></div>
                                            @if ($p->paid_at)
                                                <div class="text-xs text-gray-500">Zapłacono:
                                                    {{ $p->paid_at_formatted }}</div>
                                            @endif
                                        </div>

                                        <div class="flex flex-col gap-1 w-full">
                                            <form action="{{ route('payments.updateStatus', $p->id) }}" method="POST"
                                                class="inline-flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="border rounded p-1 text-sm">
                                                    <option value="awaiting" @selected($p->status == 'awaiting')>Oczekuje
                                                    </option>
                                                    <option value="paid" @selected($p->status == 'paid')>Zapłacone
                                                    </option>
                                                    <option value="overdue" @selected($p->status == 'overdue')>Zaległe
                                                    </option>
                                                </select>
                                                <button
                                                    class="px-2 py-1 bg-gray-100 rounded text-sm hover:bg-gray-200">Zmień</button>
                                            </form>

                                            <form action="{{ route('payments.markPaid', $p->id) }}" method="POST"
                                                class="">
                                                @csrf
                                                <button
                                                    class="px-2 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Oznacz
                                                    jako zapłacone</button>
                                            </form>
                                        </div>
                                    @else
                                        <form action="{{ route('payments.store') }}" method="POST"
                                            class="space-y-2 w-full">
                                            @csrf
                                            <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                            <div class="flex gap-2 items-center justify-end w-full">
                                                <input name="amount" type="number" step="0.01" placeholder="Kwota"
                                                    class="border rounded p-1 w-28 text-sm" required>
                                                <input name="notes" type="text" placeholder="Notatka"
                                                    class="border rounded p-1 text-sm flex-1">
                                            </div>
                                            <input type="hidden" name="status" value="awaiting">
                                            <div class="flex justify-end w-full">
                                                <button class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">Dodaj
                                                    płatność</button>
                                            </div>
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

        <form action="{{ route('dashboard.delete', $student->id) }}" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Usuń ucznia</button>
        </form>
    </div>
</x-layout>
