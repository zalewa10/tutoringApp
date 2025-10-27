<x-layout>
<form action="{{ route('login') }}" method="POST">
  @csrf

  <h2>Zaloguj się</h2>

  <label for="email">Email:</label>
  <input 
    type="email"
    name="email"
    required
    value="{{ old('email') }}"
  >

  <label for="password">Hasło:</label>
  <input 
    type="password"
    name="password"
    required
  >

  <button type="submit" class="btn mt-4">Zaloguj się</button>

  <p class="mt-4">
    Nie masz konta? 
    <a href="{{ route('show.register') }}" class="text-blue-500 underline">Zarejestruj się</a>

  <!-- validation errors -->
   @if ($errors->any())
  <ul class="px-4 py-2 bg-red-100">
    @foreach ($errors->all() as $error)
      <li class="my-2 text-red-500">{{ $error }}</li>
    @endforeach
  </ul>
  @endif
</form>
</x-layout>