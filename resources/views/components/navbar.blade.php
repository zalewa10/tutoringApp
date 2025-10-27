 <header>
     <nav>
         <h1>
             Tutoring App
         </h1>         
         @guest
            <a href="{{ route('show.register') }}" class="btn">Utwórz konto</a>
            <a href="{{ route('show.login') }}" class="btn">Zaloguj się</a>
         @endguest

         @auth
         <span class="border-r-2 pr-2">Witaj {{ Auth::user()->name }}</span>
            <a href="{{ route('dashboard.create') }}">Utwórz ucznia</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn">Wyloguj</button>
            </form>
         @endauth
     </nav>
 </header>
