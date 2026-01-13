<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid px-4">

        {{-- Brand / Logo --}}
        <a class="navbar-brand fw-semibold d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="fa fa-university me-2"></i>
            {{ config('app.name') }}
        </a>

        {{-- Toggle Mobile --}}
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarMain" aria-controls="navbarMain"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Menu --}}
        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- Left menu --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i class="fa fa-chart-line me-1"></i> Dashboard
                    </a>
                </li>
            </ul>

            {{-- Right menu --}}
            <ul class="navbar-nav ms-auto">

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center"
                       href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">

                        <i class="fa fa-user-circle me-2"></i>
                        {{ Auth::user()->name }}
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">

                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                <i class="fa fa-user me-2 text-muted"></i> Profile
                            </a>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa fa-sign-out-alt me-2"></i> Log Out
                                </button>
                            </form>
                        </li>

                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>
