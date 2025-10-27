<x-layout>
    <div class="p-4">
        <h2>{{ $student->name }}!</h2>
        <p><strong>Telefon: </strong>{{ $student->tel }}</p>
        <p><strong>Stawka: </strong>{{ $student->rate }} PLN </p>
        <p><strong>Aktywny: </strong>{{ $student->active ? 'Tak' : 'Nie' }}</p>
    </div>

<a class="btn" href="{{ route('dashboard.index') }}">Powrót na stronę główną</a>
<form action="{{ route('dashboard.delete', $student->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn my-4">Usuń ucznia</button>
</form>

</x-layout>