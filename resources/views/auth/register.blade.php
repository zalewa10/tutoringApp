<x-layout>
    <div class="flex items-center justify-center bg-white">
        <div class="w-full max-w-md p-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Utwórz konto</h1>
                <p class="text-gray-500 mt-1">Zarejestruj się, aby zacząć zarządzać zajęciami</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Imię</label>
                        <input placeholder="Krzysztof" type="text" name="name" required value="{{ old('name') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nazwisko</label>
                        <input placeholder="Zalewski" type="text" name="surname" required value="{{ old('surname') }}"
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('surname')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input placeholder="email@gmail.com" type="email" name="email" required value="{{ old('email') }}"
                        class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hasło</label>
                        <input type="password" name="password" required
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Potwierdź</label>
                        <input type="password" name="password_confirmation" required
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2.5 rounded-lg shadow-sm hover:bg-indigo-700 transition">
                    Zarejestruj się
                </button>
            </form>

            @if ($errors->any())
                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-6 text-center text-sm text-gray-600">
                <span>Masz już konto?</span>
                <a href="{{ route('login') }}"
                    class="text-indigo-600 hover:text-indigo-700 font-medium">Zaloguj się</a>
            </div>
        </div>
    </div>
</x-layout>
