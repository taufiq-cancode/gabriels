<nav class="container navbar py-4" >
    <div class="container-fluid">
        <span class="navbar-text">
            @if(Auth::user() && Auth::user()->role === 'admin')
                <h3>Welcome, Admin! &#128075;</h3>
            @else
                <h3>Welcome, {{ Auth::user()->firstname }}! &#128075; </h3>
            @endif
        </span>
        <form action="{{ route('logout') }}" method="POST" class="d-flex">
            @csrf
            <button type="submit" class="btn btn-danger">
                Logout
            </button>
        </form>
    </div>
</nav>

<hr class="container" style="margin-top: -5px">
