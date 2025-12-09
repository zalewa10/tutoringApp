<x-layout>
    <div class="max-w-5xl mx-auto mt-8 space-y-6">
        <!-- Student Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $student->name }} {{ $student->surname }}</h1>
                    <div class="mt-3 space-y-1 text-sm text-gray-600">
                        @if ($student->tel)
                            <p><span class="font-semibold">Telefon:</span> {{ $student->tel }}</p>
                        @endif
                        <p><span class="font-semibold">Stawka:</span> <span
                                class="text-lg font-medium text-indigo-600">{{ $student->rate }} PLN</span></p>
                        <p>
                            <span class="font-semibold">Status:</span>
                            <span
                                class="inline-block ml-2 px-3 py-1 rounded-full text-xs font-medium {{ $student->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $student->active ? 'Aktywny' : 'Nieaktywny' }}
                            </span>
                        </p>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('dashboard.edit', $student->id) }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium text-center">
                        Edytuj dane
                    </a>
                    <a href="{{ route('dashboard.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 font-medium text-center">
                        Powrót
                    </a>
                </div>
            </div>
        </div>

        <!-- Lessons Section -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Lekcje</h2>
                <button onclick="toggleLessonForm()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium text-center md:w-auto w-full">
                    + Dodaj lekcję
                </button>
            </div>

            <!-- Add Lesson Form (Hidden by default) -->
            <div id="lessonFormContainer" class="hidden bg-gray-50 rounded-lg p-5 mb-6 border border-gray-200">
                <form action="{{ route('lessons.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $student->id }}">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tytuł (opcjonalny)</label>
                        <input name="title" type="text" placeholder="Np. Matematyka - Równania"
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start *</label>
                            <input name="start" type="datetime-local" required
                                class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Koniec (opcjonalny)</label>
                            <input name="end" type="datetime-local"
                                class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notatka</label>
                        <textarea name="notes" rows="3"
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            placeholder="Dodaj notatki do tej lekcji..."></textarea>
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-medium">
                            Dodaj lekcję
                        </button>
                        <button type="button" onclick="toggleLessonForm()"
                            class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded font-medium">
                            Anuluj
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lessons List -->
            @if ($lessons->count() > 0)
                <div class="space-y-4">
                    @foreach ($lessons as $lesson)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                                <!-- Lesson Details -->
                                <div class="flex-1">
                                    <div class="flex items-start gap-3 mb-2">
                                        <div>
                                            <h3 class="font-semibold text-gray-800 text-lg">
                                                {{ $lesson->title ?? 'Lekcja' }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                📅 {{ $lesson->start ? $lesson->start->format('d.m.Y H:i') : '—' }}
                                                @if ($lesson->end)
                                                    — {{ $lesson->end->format('H:i') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @if ($lesson->notes)
                                        <p
                                            class="text-sm text-gray-600 mt-2 bg-blue-50 p-2 rounded border-l-4 border-blue-300">
                                            {{ $lesson->notes }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Lesson Actions -->
                                <div class="flex flex-col gap-2 md:w-auto">
                                    <a href="{{ route('lessons.show', $lesson->id) }}"
                                        class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium text-center">
                                        Szczegóły
                                    </a>
                                    <a href="{{ route('lessons.edit', $lesson->id) }}"
                                        class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm font-medium text-center">
                                        Edytuj
                                    </a>
                                </div>
                            </div>

                            <!-- Payment Section -->
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                @php $p = $lesson->payment; @endphp
                                @if ($p)
                                    <div class="bg-gray-50 rounded p-3">
                                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
                                            <div>
                                                <p class="text-xs text-gray-500">Kwota</p>
                                                <p class="font-semibold text-gray-800">
                                                    {{ number_format($p->amount, 2, '.', '') }} PLN</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Status</p>
                                                <p
                                                    class="inline-block px-2 py-1 rounded text-xs font-medium
                                                    @if ($p->status === 'paid') bg-green-100 text-green-700
                                                    @elseif ($p->status === 'overdue')
                                                        bg-red-100 text-red-700
                                                    @else
                                                        bg-yellow-100 text-yellow-700 @endif
                                                ">
                                                    @switch ($p->status)
                                                        @case ('paid')
                                                            Zapłacone
                                                        @break

                                                        @case ('overdue')
                                                            Zaległe
                                                        @break

                                                        @default
                                                            Oczekuje
                                                    @endswitch
                                                </p>
                                            </div>
                                            @if ($p->paid_at)
                                                <div>
                                                    <p class="text-xs text-gray-500">Zapłacono</p>
                                                    <p class="text-sm text-gray-700">{{ $p->paid_at->format('d.m.Y') }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <form action="{{ route('payments.updateStatus', $p->id) }}" method="POST"
                                                class="flex gap-2 flex-1">
                                                @csrf
                                                @method('PUT')
                                                <select name="status"
                                                    class="flex-1 border border-gray-300 rounded p-2 text-sm">
                                                    <option value="awaiting" @selected($p->status == 'awaiting')>Oczekuje
                                                    </option>
                                                    <option value="paid" @selected($p->status == 'paid')>Zapłacone
                                                    </option>
                                                    <option value="overdue" @selected($p->status == 'overdue')>Zaległe
                                                    </option>
                                                </select>
                                                <button type="submit"
                                                    class="px-3 py-1 bg-gray-300 rounded text-sm hover:bg-gray-400 font-medium">
                                                    Zmień
                                                </button>
                                            </form>
                                            <form action="{{ route('payments.markPaid', $p->id) }}" method="POST"
                                                class="flex-1">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full px-3 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700 font-medium">
                                                    ✓ Zapłacone
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <form action="{{ route('payments.store') }}" method="POST" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                        <p class="text-sm text-gray-500 mb-2">Brak powiązanej płatności. Dodaj nową:</p>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                            <input name="amount" type="number" step="0.01" placeholder="Kwota"
                                                class="border border-gray-300 rounded p-2 text-sm" required>
                                            <input name="notes" type="text" placeholder="Notatka (opcjonalnie)"
                                                class="border border-gray-300 rounded p-2 text-sm">
                                            <div>
                                                <input type="hidden" name="status" value="awaiting">
                                                <button type="submit"
                                                    class="w-full px-3 py-2 bg-indigo-600 text-white rounded text-sm hover:bg-indigo-700 font-medium">
                                                    + Dodaj
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <p class="text-gray-500 text-lg">Brak zaplanowanych lekcji</p>
                    <button onclick="toggleLessonForm()"
                        class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium">
                        Zaplanuj lekcję
                    </button>
                </div>
            @endif
        </div>

        <!-- Danger Zone -->
        <div class="bg-red-50 border border-red-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-red-900 mb-3">Strefa niebezpieczna</h3>
            <p class="text-sm text-red-700 mb-4">Usunięcie ucznia spowoduje usunięcie wszystkich powiązanych lekcji i
                płatności.</p>
            <form action="{{ route('dashboard.delete', $student->id) }}" method="POST"
                onsubmit="return confirm('Czy na pewno chcesz usunąć tego ucznia? Ta operacja nie może być cofnięta.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-medium">
                    Usuń ucznia
                </button>
            </form>
        </div>
    </div>

    <script>
        function toggleLessonForm() {
            const container = document.getElementById('lessonFormContainer');
            container.classList.toggle('hidden');
            if (!container.classList.contains('hidden')) {
                container.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    </script>
</x-layout>
