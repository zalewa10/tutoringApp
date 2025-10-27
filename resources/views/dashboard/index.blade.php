<x-layout>
    <h1>Lista wszystkich uczniów</h1>

  
    <ul>
        @foreach ($students as $student)
            <li>
               <x-card href="{{ route('dashboard.show', $student->id) }}">
                <div>
                    <h3>{{ $student->name }} {{ $student->surname }}</h3>
                </div>
               </x-card>
            </li>
        @endforeach
    </ul>

    {{ $students->onEachSide(5)->links() }}

</x-layout>