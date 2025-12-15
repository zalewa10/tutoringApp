<x-layout>
        <div class="flex items-center justify-center bg-white">
            <div class="w-full max-w-md p-8">
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-900">Logowanie</h1>
                    <p class="text-gray-500 mt-1">Wprowadź swoje dane, aby kontynuować</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input placeholder="email@gmail.com"  id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('email')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Hasło</label>
                        <input id="password" type="password" name="password" required
                            class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                        <label class="flex items-center gap-2 text-sm text-gray-700 w-full">
                            <input type="checkbox" name="remember" class="rounded border-gray-300" />
                            Zapamiętaj mnie
                        </label>
                  
                    

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-2.5 rounded-lg shadow-sm hover:bg-blue-700 transition">
                        Zaloguj
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-600">
                    <span>Nie masz konta?</span>
                    <a href="{{ route('register') }}"
                        class="text-blue-600 hover:text-blue-700 font-medium">Zarejestruj się</a>
                </div>
            </div>
        </div>
    </div>
</x-layout>
