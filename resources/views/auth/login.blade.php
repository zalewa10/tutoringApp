<x-layout>
    <div class="max-w-sm mx-auto mt-16 bg-white p-8 rounded-lg shadow">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Zaloguj sięę</h2>

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="text-sm text-gray-700">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>

            <div>
                <label class="text-sm text-gray-700">Hasło</label>
                <input type="password" name="password" required
                    class="mt-1 block w-full border border-gray-300 rounded p-2 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded">
                Zaloguj się
            </button>

            <p class="text-sm text-center text-gray-600">
                Nie masz konta?
                <a href="{{ route('show.register') }}" class="text-indigo-600 hover:underline">Zarejestruj się</a>
            </p>

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
