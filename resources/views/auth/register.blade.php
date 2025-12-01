<x-layout>
    <div class="max-w-md mx-auto mt-12 bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Utwórz konto</h2>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-700">Imię</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div>
                    <label class="text-sm text-gray-700">Nazwisko</label>
                    <input type="text" name="surname" required value="{{ old('surname') }}"
                        class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-700">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm text-gray-700">Hasło</label>
                    <input type="password" name="password" required
                        class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div>
                    <label class="text-sm text-gray-700">Potwierdź hasło</label>
                    <input type="password" name="password_confirmation" required
                        class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                Zarejestruj się
            </button>

            @if ($errors->any())
                <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>
    </div>
</x-layout>
