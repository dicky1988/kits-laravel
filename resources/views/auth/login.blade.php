<x-guest-layout>
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-5 col-lg-4">

                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4 p-md-5">

                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-user-shield fa-3x text-primary"></i>
                            </div>
                            <h4 class="fw-bold mb-1">Selamat Datang</h4>
                            <p class="text-muted small">
                                Silakan login untuk melanjutkan
                            </p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-3" :status="session('status')" />

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Username -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Username</label>
                                <input
                                    type="text"
                                    name="username"
                                    class="form-control form-control-lg @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}"
                                    autofocus
                                    required
                                >
                                @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group input-group-lg">
                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        required
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword()"
                                    >
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <!-- Remember -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                    >
                                    <label class="form-check-label small" for="remember">
                                        Remember me
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a class="small text-decoration-none" href="{{ route('password.request') }}">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>

                            <!-- Submit -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                    <i class="fa fa-sign-in-alt me-2"></i> Login
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <p class="text-center text-muted small mt-4">
                    © {{ date('Y') }} BPKP
                </p>

            </div>
        </div>
    </div>

    <!-- Show Password -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-guest-layout>
