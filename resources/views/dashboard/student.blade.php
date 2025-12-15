<x-layout>
    <div class="h-16 p-4 bg-white border-b border-gray-200 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h1>{{ $student->name }} {{ $student->surname }}</h1>
            @if ($student->tel)
                <span class="text-sm text-gray-600">📞 {{ $student->tel }}</span>
            @endif
            <span class="text-sm font-semibold text-blue-600">{{ $student->rate }} PLN</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dashboard.edit', $student->id) }}" class="px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">Edytuj</a>
            <form action="{{ route('dashboard.delete', $student->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć tego ucznia?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium">Usuń</button>
            </form>
            <a href="{{ route('students.index') }}" class="px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium">Powrót</a>
        </div>
    </div>

    <div class="p-4">
        <!-- Add Lesson Button -->
        <div class="mb-4">
            <button onclick="toggleLessonForm()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                + Dodaj lekcję
            </button>
        </div>

        <!-- Add Lesson Form (Hidden by default) -->
        <div id="lessonFormContainer" class="hidden mb-4 bg-white rounded-lg shadow p-4 border border-gray-200">
            <form action="{{ route('lessons.store') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <input name="title" type="text" placeholder="Tytuł (opcjonalny)" class="border border-gray-300 rounded p-2 text-sm">
                    <input name="start" type="datetime-local" required class="border border-gray-300 rounded p-2 text-sm">
                    <input name="end" type="datetime-local" placeholder="Koniec (opcjonalny)" class="border border-gray-300 rounded p-2 text-sm">
                    <input name="notes" type="text" placeholder="Notatka" class="border border-gray-300 rounded p-2 text-sm">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">Dodaj</button>
                    <button type="button" onclick="toggleLessonForm()" class="flex-1 px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-sm font-medium">Anuluj</button>
                </div>
            </form>
        </div>

        <!-- Lessons Grid (Kanban style) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($lessons as $lesson)
                @php $p = $lesson->payment; @endphp
                <div class="bg-white rounded-lg shadow border border-gray-200 hover:shadow-lg transition">
                    <div class="p-4">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="font-semibold text-gray-800">{{ $lesson->title ?? 'Lekcja' }}</h3>
                            @if ($p)
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $p->status === 'zapłacone' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-600 space-y-1 mb-3">
                            <p>📅 {{ $lesson->start ? $lesson->start->format('d.m.Y H:i') : '—' }}
                                @if ($lesson->end)
                                    — {{ $lesson->end->format('H:i') }}
                                @endif
                            </p>
                            @if ($p)
                                <p class="font-medium text-blue-600">💰 {{ number_format($p->amount, 2) }} PLN</p>
                            @endif
                            @if ($lesson->notes)
                                <p class="text-xs text-gray-500 italic mt-2">{{ $lesson->notes }}</p>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2 pt-3 border-t border-gray-200">
                            @if ($p && $p->status === 'oczekuje')
                                <form action="{{ route('payments.markPaid', $p->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-xs font-medium">
                                        ✓ Zapłacono
                                    </button>
                                </form>
                            @elseif (!$p)
                                <button onclick="togglePaymentForm({{ $lesson->id }})" class="flex-1 px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium">
                                    + Płatność
                                </button>
                            @endif
                            <a href="{{ route('lessons.edit', $lesson->id) }}" class="flex-1 px-2 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-xs font-medium text-center">
                                Edytuj
                            </a>
                            <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" onsubmit="return confirm('Czy na pewno chcesz usunąć tę lekcję?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-medium">
                                    Usuń
                                </button>
                            </form>
                        </div>

                        <!-- Payment Form (Hidden by default) -->
                        @if (!$p)
                            <div id="paymentForm{{ $lesson->id }}" class="hidden mt-3 pt-3 border-t border-gray-200">
                                <form action="{{ route('payments.store') }}" method="POST" class="space-y-2">
                                    @csrf
                                    <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                    <input name="amount" type="number" step="0.01" placeholder="Kwota" class="w-full border border-gray-300 rounded p-2 text-sm" required>
                                    <input type="hidden" name="status" value="oczekuje">
                                    <div class="flex gap-2">
                                        <button type="submit" class="flex-1 px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-medium">Dodaj</button>
                                        <button type="button" onclick="togglePaymentForm({{ $lesson->id }})" class="flex-1 px-3 py-1 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 text-xs font-medium">Anuluj</button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-lg shadow border-2 border-dashed border-gray-300 p-12 text-center">
                    <p class="text-gray-500 mb-3">Brak lekcji</p>
                    <button onclick="toggleLessonForm()" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">
                        Dodaj pierwszą lekcję
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function toggleLessonForm() {
            const container = document.getElementById('lessonFormContainer');
            container.classList.toggle('hidden');
        }

        function togglePaymentForm(lessonId) {
            const form = document.getElementById('paymentForm' + lessonId);
            form.classList.toggle('hidden');
        }
    </script>
</x-layout>
