<nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">{{ $title }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('home') }}">
                            <i class="fa-solid fa-house me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('peta') }}">
                            <i class="fa-solid fa-map-location-dot me-1"></i>Peta
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tabel') }}">
                            <i class="fa-solid fa-table me-1"></i>Tabel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tabel') }}">
                            <i class="fa-solid fa-circle-info me-1"></i>Tentang
                        </a>
                    </li>
                    @guest
                    <li class="nav-item ms-2">
    <a class="btn btn-primary rounded-pill px-3" href="{{ route('login') }}">
        <i class="fa-solid fa-user me-1"></i>
        Login
    </a>
</li>
                    @endguest
                    @auth
                    <li class="nav-item ms-2">
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-danger rounded-pill px-3">
                                <i class="fa-solid fa-right-from-bracket me-1"></i>
                                Logout
                            </button>
                        </form>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
