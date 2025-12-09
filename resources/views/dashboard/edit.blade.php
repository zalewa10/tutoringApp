<x-layout>
    <div class="max-w-2xl mx-auto mt-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Edytuj ucznia</h1>

            <form action="{{ route('dashboard.update', ['id' => $student->id]) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Imię *</label>
                        <input type="text" id="name" name="name" value="{{ $student->name }}" required
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nazwisko *</label>
                        <input type="text" id="surname" name="surname" value="{{ $student->surname }}" required
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Telefon</label>
                        <input type="tel" id="tel" name="tel" value="{{ $student->tel }}"
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Stawka za godzinę *</label>
                        <input type="number" id="rate" name="rate" value="{{ $student->rate }}" step="0.01"
                            required
                            class="w-full border border-gray-300 rounded p-3 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kolor ucznia</label>
                        <div class="flex gap-2 items-center">
                            <input type="color" id="color" name="color"
                                value="{{ $student->color ?? '#ef4444' }}"
                                class="h-12 w-20 border border-gray-300 rounded cursor-pointer">
                            <span id="colorValue"
                                class="text-sm text-gray-600 font-mono">{{ $student->color ?? '#ef4444' }}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">Ten kolor będzie wyświetlany na lekcjach w kalendarzu</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="active" name="active" value="1"
                                @checked($student->active)
                                class="w-5 h-5 rounded border-gray-300 focus:ring-indigo-400">
                            <span class="text-gray-700">Uczestnik aktywny</span>
                        </label>
                    </div>
                </div>

                <div class="flex gap-3 pt-6 border-t">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium">
                        Zapisz zmiany
                    </button>
                    <a href="{{ route('dashboard.show', $student->id) }}"
                        class="flex-1 px-6 py-3 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 font-medium text-center">
                        Anuluj
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const colorInput = document.getElementById('color');
        const colorValue = document.getElementById('colorValue');

        colorInput.addEventListener('input', function() {
            colorValue.textContent = this.value.toUpperCase();
        });
    </script>
</x-layout>
