<style>
    .sidebar {
        background: white;
        min-height: 100svh;
        width: 200px !important;
    }


    .sidebar-label {
        transition: opacity 0.2s;
        opacity: 1;
        white-space: nowrap;
        margin-left: 8px;
        font-size: 15px;
    }


    .sidebar .sidebar-label {
        opacity: 1;
    }

    .sidebar-nav-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        font-weight: 500;
        transition: background 0.15s;
        text-decoration: none;
    }

    .sidebar-nav-link:hover,
    .sidebar-nav-link.active {
        background: #27272a;
        color: #fff;
    }

    .sidebar-nav-link .icon {
        font-size: 1.25rem;
        min-width: 24px;
        text-align: center;
    }

    .sidebar-header {
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .sidebar-footer {
        margin-top: auto;
    }

    .sidebar .sidebar-nav-link {
        position: relative;
    }

    .sidebar-nav-link.disabled {
        pointer-events: none;
        opacity: 0.5;
    }
</style>


<aside id="sidebar"
    class="sidebar border-r border-gray-200 sidebar-collapsed fixed left-0 top-0 bottom-0 z-40 flex flex-col transition-all duration-300">
    <div class="sidebar-header h-14 border-b border-gray-200">
        <a href="{{ route('dashboard.index') }}" class="sidebar-label font-bold text-xl text-black">TutorApp</a>
    </div>
    <nav class="flex flex-col gap-1 p-4 ">

        @auth
            <a href="{{ route('dashboard.index') }}" class="sidebar-nav-link @if (request()->routeIs('dashboard.index')) active @endif">
                <span class="icon"><i class="fas fa-home"></i></span>
                Kalendarz</a>
            <a href="{{ route('students.index') }}"
                class="sidebar-nav-link @if (request()->routeIs('students.index')) active @endif"> <span class="icon"><i
                        class="fas fa-home"></i></span>
                Uczniowie</a>
            <a href="{{ route('finance.index') }}" class="sidebar-nav-link @if (request()->routeIs('finance.index')) active @endif">
                <span class="icon"><i class="fas fa-home"></i></span>
                Finanse</a>
            <a href="{{ route('history.index') }}" class="sidebar-nav-link @if (request()->routeIs('history.index')) active @endif">
                <span class="icon"><i class="fas fa-home"></i></span>
                Historia</a>
        @else
            <a href="{{ route('show.login') }}" class="sidebar-nav-link"> <span class="icon"><i
                        class="fas fa-home"></i></span>
                Logowanie</a>
            <a href="{{ route('show.register') }}" class="sidebar-nav-link"> <span class="icon"><i
                        class="fas fa-home"></i></span>
                Rejestracja</a>
        @endauth
    </nav>
    <div class="sidebar-footer p-4 border-t border-gray-200">
        @auth

            <div class="text-lg ">{{ auth()->user()->name }}</div>
            {{-- <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button class="">x</button>
            </form> --}}

        @endauth

    </div>
</aside>
