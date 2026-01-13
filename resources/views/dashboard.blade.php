@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="row mb-4">
            <div class="col">
                <h3 class="fw-semibold mb-0">
                    Dashboard
                </h3>
                <p class="text-muted mb-0">
                    Selamat datang kembali, <strong>{{ Auth::user()->name }}</strong>
                </p>
            </div>
        </div>

        {{-- Card utama --}}
        <div class="row">
            <div class="col-md-6 col-lg-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                 style="width:60px;height:60px;">
                                <i class="fa fa-user-check fa-lg text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-1 fw-semibold">
                                    Status Login
                                </h6>
                                <span class="badge bg-success">
                                Anda berhasil login
                            </span>
                            </div>
                        </div>

                        <hr>

                        <p class="text-muted mb-0">
                            Anda telah berhasil masuk ke sistem menggunakan akun:
                        </p>
                        <p class="fw-semibold mb-0">
                            {{ Auth::user()->email }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
