<x-layout>
    <form action="{{ route('dashboard.store') }}" method="POST">
        @csrf
        <h2>Stwórz nowego ucznia</h2>

        <!-- Imię ucznia -->
        <label for="name">Imię ucznia:</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required>

        <!-- Nazwisko ucznia -->
        <label for="surname">Nazwisko ucznia:</label>
        <input type="text" id="surname" name="surname" value="{{ old('surname') }}">

        <!-- Tel -->
        <label for="tel">Telefon:</label>
        <input type="tel" id="tel" name="tel" value="{{ old('tel') }}">

        <!-- Stawka -->
        <label for="rate">Stawka:</label>
        <input type="number" id="rate" name="rate" value="{{ old('rate') }}" required>

        <button type="submit" class="btn mt-4">Stwórz Ucznia</button>

        <!-- validation errors -->


        @if ($errors->any())

            <ul class="p-4 bg-red-100 my-2">
                @foreach ($errors->all() as $error)
                    <li class="my-2 text-red-500">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

    </form>
</x-layout>
