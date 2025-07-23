<header class="header">
    <div class="header-logo">
        <a href="{{ route('dashboard') }}">VaLibrary</a>
    </div>

    <nav class="main-nav">
        <ul>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="#">Loans</a></li>
            <li><a href="/books">Books</a></li>

            <li class="dropdown">
                <a href="#">Users</a>
                <ul class="dropdown-menu">
                    <li><a href="#">All Users</a></li>
                    <li><a href="#">Add New User</a></li>
                    <li><a href="#">Invitation Codes</a></li>
                </ul>
            </li>

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-button">Log Out</button>
                </form>
            </li>
        </ul>
    </nav>
</header>
