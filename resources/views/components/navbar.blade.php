<style>
    .sidebar {
        background: white;
        color: black;
        min-height: 100svh;
        width: 200px !important;
        transition: background-color 0.2s, color 0.2s;
    }

    html.dark .sidebar {
        background: #1f2937;
        color: white;
        border-color: #374151;
    }

    .sidebar-label {
        transition: opacity 0.2s;
        opacity: 1;
        white-space: nowrap;
        margin-left: 8px;
        font-size: 15px;
        color: inherit;
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
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
        color: inherit;
    }

    .sidebar-nav-link:hover,
    .sidebar-nav-link.active {
        background: #27272a;
        color: #fff;
    }

    html.dark .sidebar-nav-link:hover,
    html.dark .sidebar-nav-link.active {
        background: #2563eb;
        color: white;
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
        border-color: inherit;
    }

    html.dark .sidebar-header {
        border-color: #374151;
    }

    .sidebar-footer {
        margin-top: auto;
        border-color: inherit;
    }

    html.dark .sidebar-footer {
        border-color: #374151;
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
            <div class="mb-4">
                <div class="text-lg">Witaj, {{ auth()->user()->name }}</div>
            </div>
            <div class="theme-switcher">
                <button id="themeLight" class="light-btn" title="Jasny motyw">☀️</button>
                <button id="themeDark" class="dark-btn" title="Ciemny motyw">🌙</button>
            </div>
        @endauth
    </div>

</aside>
