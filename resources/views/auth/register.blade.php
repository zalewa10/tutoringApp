<x-layout>
<form action="{{ route('register') }}" method="POST">
  @csrf

  <h2>Utwórz konto</h2>


  <label for="name">Imię:</label>
  <input 
    type="text"
    name="name"
    required
    value="{{ old('name') }}"
  >
  
  <label for="surname">Nazwisko:</label>
  <input 
    type="text"
    name="surname"
    required
    value="{{ old('surname') }}"
  >



  <label for="email">Email:</label>
  <input 
    type="email"
    name="email"
    required
    value="{{ old('email') }}"
  >



  <label for="password">Password:</label>
  <input 
    type="password"
    name="password"
    required
  >
  <label for="password_confirmation"> Confirm password:</label>
  <input 
    type="password"
    name="password_confirmation"
    required
  >

  <button type="submit" class="btn mt-4">Register</button>

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
