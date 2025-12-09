<x-layout>
    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Edytuj lekcję</h1>

            <form action="{{ route('lessons.update', $lesson->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Uczeń *</label>
                    <select name="student_id" required
                        class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">— wybierz ucznia —</option>
                        @foreach ($allStudents as $student)
                            <option value="{{ $student->id }}" @selected($student->id === $lesson->student_id)>
                                {{ $student->name }} {{ $student->surname }}
                            </option>
                        @endforeach
                    </select>
                    @error('student_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tytuł (opcjonalny)</label>
                    <input type="text" name="title" value="{{ old('title', $lesson->title) }}"
                        class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Np. Matematyka - Równania">
                    @error('title')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start *</label>
                        <input type="datetime-local" name="start"
                            value="{{ old('start', $lesson->start ? $lesson->start->format('Y-m-d\TH:i') : '') }}"
                            required
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @error('start')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Koniec (opcjonalny)</label>
                        <input type="datetime-local" name="end"
                            value="{{ old('end', $lesson->end ? $lesson->end->format('Y-m-d\TH:i') : '') }}"
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @error('end')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notatka (opcjonalnie)</label>
                    <textarea name="notes" rows="4"
                        class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        placeholder="Dodaj notatki do tej lekcji...">{{ old('notes', $lesson->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-6 border-t">
                    <button type="submit"
                        class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium">
                        Zapisz zmiany
                    </button>
                    <a href="{{ route('lessons.show', $lesson->id) }}"
                        class="px-6 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 font-medium">
                        Anuluj
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layout>
