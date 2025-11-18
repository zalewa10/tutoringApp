<x-layout>
<h2>Na tej stronie będzie edycja ucznia: {{ $student->name }}</h2>

<form action="{{ route('dashboard.update', ['id' => $student->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <label for="name">Imię:</label>
    <input type="text" id="name" name="name" value="{{ $student->name }}" required>

    <label for="tel">Telefon:</label>
    <input type="tel" id="tel" name="tel" value="{{ $student->tel }}" required>

    <label for="rate">Stawka za godzinę:</label>
    <input type="number" id="rate" name="rate" value="{{ $student->rate }}" step="0.01" required>

    <button class="btn" type="submit">Zapisz zmiany</button>
</form>
</x-layout>