<header class="header">
    <div class="header-logo">
        <a href="{{ route('dashboard') }}">VaLibrary</a>
    </div>

    <nav class="main-nav">
        <ul>
            @include('partials._nav_items', ['items' => $mainNav])
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Log Out</button>
                </form>
            </li>
        </ul>
    </nav>
</header>
