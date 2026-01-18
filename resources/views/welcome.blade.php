<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font Awesome (opsional) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="min-vh-100 d-flex align-items-center justify-content-center">

    <div class="text-center" style="max-width: 420px; width: 100%;">

        {{-- NAMA APLIKASI --}}
        <h1 class="fw-bold mb-1">
            SIM JFA
        </h1>

        <p class="text-muted mb-4">
            Sistem Informasi Manajemen Pusbin JFA
        </p>

        {{-- CARD LOGIN / REGISTER --}}
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">

                @auth
                    <a href="{{ route('dashboard') }}"
                       class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="fa fa-gauge me-1"></i>
                        Masuk ke Dashboard
                    </a>
                @else
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}"
                           class="btn btn-primary py-2 fw-semibold">
                            <i class="fa fa-right-to-bracket me-1"></i>
                            Login
                        </a>

                        @if (Route::has('register'))
                            {{--<a href="{{ route('register') }}"
                               class="btn btn-outline-secondary py-2 fw-semibold">
                                <i class="fa fa-user-plus me-1"></i>
                                Register
                            </a>--}}
                        @endif
                    </div>
                @endauth

            </div>
        </div>

        {{-- FOOTER --}}
        <small class="text-muted d-block mt-4">
            © {{ date('Y') }} {{ config('app.name') }}
        </small>

    </div>

</div>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
