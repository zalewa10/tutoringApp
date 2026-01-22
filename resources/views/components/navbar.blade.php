

<aside id="sidebar"
    class="sidebar border-r border-gray-200 sidebar-collapsed fixed left-0 top-0 bottom-0 z-40 flex flex-col transition-all duration-300">
    <div class="sidebar-header h-16 border-b border-gray-200">
        <a href="{{ route('dashboard.index') }}" class="sidebar-label font-bold text-xl ">TutorApp</a>
    </div>
    <nav class="flex flex-col gap-1 p-4 ">

        @auth
            <a href="{{ route('dashboard.index') }}" class="sidebar-nav-link @if (request()->routeIs('dashboard.index')) active @endif">
                <span class="icon"><i class="fas fa-calendar-alt"></i></span>
                <span class="sidebar-label">Kalendarz</span>
            </a>
            <a href="{{ route('students.index') }}" class="sidebar-nav-link @if (request()->routeIs('students.index')) active @endif">
                <span class="icon"><i class="fas fa-users"></i></span>
                <span class="sidebar-label">Uczniowie</span>
            </a>
            <a href="{{ route('history.index') }}" class="sidebar-nav-link @if (request()->routeIs('history.index')) active @endif">
                <span class="icon"><i class="fas fa-history"></i></span>
                <span class="sidebar-label">Historia</span>
            </a>
        @else
            <a href="{{ route('show.login') }}" class="sidebar-nav-link">
                <span class="icon"><i class="fas fa-sign-in-alt"></i></span>
                <span class="sidebar-label">Logowanie</span>
            </a>
            <a href="{{ route('show.register') }}" class="sidebar-nav-link">
                <span class="icon"><i class="fas fa-user-plus"></i></span>
                <span class="sidebar-label">Rejestracja</span>
            </a>
        @endauth
    </nav>
    <div class="sidebar-footer p-4 border-t border-gray-200">
        @auth
            <div class="mb-4 flex items-center justify-between">
                <div class="text-lg">Witaj, {{ auth()->user()->name }}</div>
                  <button id="themeToggle" class="theme-toggle-btn" title="Przełącz motyw">
                    <span class="light-icon">☀️</span>
                    <span class="dark-icon">🌙</span>
                </button>
            </div>
           
        @endauth
    </div>

</aside>
