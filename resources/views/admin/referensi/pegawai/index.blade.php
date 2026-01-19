@extends('layouts.app')

<style>
    .bg-light-hover:hover {
        background-color: #f8f9fa;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>

@section('content')

    <div class="container-fluid px-3">

        @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs ?? []])

        <div class="card shadow-sm mt-3">

            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-0 fw-semibold">
                            <i class="fa fa-list me-2 text-primary"></i>
                            List Pegawai
                        </h5>
                        {{--<small class="text-muted">
                            Data pengguna diambil dari API User
                        </small>--}}
                    </div>

                </div>
            </div>

            <div class="card-body">

                @if(isset($meta))
                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <form method="GET" class="d-flex align-items-center gap-2">

                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control form-control-sm"
                                   placeholder="Cari pegawai..."
                                   style="width:200px">

                            <label class="small text-muted mb-0">Tampilkan</label>

                            <select name="per_page"
                                    onchange="this.form.submit()"
                                    class="form-select form-select-sm w-auto">
                                @foreach([10,25,50,100] as $size)
                                    <option value="{{ $size }}"
                                        {{ request('per_page',10) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-search"></i>
                            </button>

                        </form>

                        <nav>
                            <ul class="pagination pagination-sm mb-0">

                                <li class="page-item {{ $meta['current_page'] == 1 ? 'disabled' : '' }}">
                                    <a class="page-link"
                                       href="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] - 1]) }}">
                                        &laquo;
                                    </a>
                                </li>

                                @for($i = 1; $i <= $meta['last_page']; $i++)
                                    <li class="page-item {{ $meta['current_page'] == $i ? 'active' : '' }}">
                                        <a class="page-link"
                                           href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">
                                            {{ $i }}
                                        </a>
                                    </li>
                                @endfor

                                <li class="page-item {{ $meta['current_page'] == $meta['last_page'] ? 'disabled' : '' }}">
                                    <a class="page-link"
                                       href="{{ request()->fullUrlWithQuery(['page' => $meta['current_page'] + 1]) }}">
                                        &raquo;
                                    </a>
                                </li>

                            </ul>
                        </nav>

                    </div>
                @endif

                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-light">
                    <tr class="text-center">
                        <th width="40"></th>
                        <th width="60" class="text-center">#</th>

                        {{--<th class="text-center">Nama</th>--}}
                        <th class="text-start text-center">
                            <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'pegawaiName',
                                    'direction' =>
                                        ($sort === 'pegawaiName' && $direction === 'asc') ? 'desc' : 'asc'
                                ]) }}" class="text-decoration-none text-dark">
                                Nama
                                @if($sort === 'pegawaiName')
                                    {!! $direction === 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        <th style="width: 10%" class="text-center">NIK</th>
                        <th style="width: 15%" class="text-center">NIP</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pegawai as $pegawai)
                        <tr>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#detail-{{ $pegawai['pegawaiNIPLama'] }}"
                                        aria-expanded="false">
                                    <i id="icon-{{ $pegawai['pegawaiNIPLama'] }}" class="fa fa-plus"></i>
                                </button>
                            </td>
                            <td class="text-center">
                                {{ (($meta['current_page'] - 1) * $meta['per_page']) + $loop->iteration }}
                            </td>
                            <td>{{ $pegawai['pegawaiName'] }}</td>
                            <td class="text-center">
                                @if(!empty($pegawai['nik']))
                                    {{ $pegawai['nik'] }}
                                @else
                                    <span class="badge bg-secondary">Belum ada NIK</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div>
                                    <span class="fw-semibold">
                                        {{ format_nip($pegawai['pegawaiNIP']) ?? '-' }}
                                    </span>
                                </div>
                                <div class="mt-1">
                                    @if(!empty($pegawai['pegawaiNIPLama']))
                                        <span>{{ $pegawai['pegawaiNIPLama'] }}</span>
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        {{-- ROW DETAIL (COLLAPSE) --}}
                        <tr class="collapse bg-light" id="detail-{{ $pegawai['pegawaiNIPLama'] }}">
                            <td colspan="8">
                                <div class="p-3">
                                    <div class="row g-3">

                                        {{-- UNIT KERJA --}}
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100 bg-white">
                                                <div class="text-muted mb-1">
                                                    <i class="fa fa-sitemap me-1"></i> Unit Kerja
                                                </div>
                                                <div class="fw-semibold">
                                                    {{ $pegawai['pegawaiUnitName'] }}
                                                </div>
                                                <div class="text-muted small">
                                                    Kode Unit: {{ $pegawai['pegawaiUnit'] }}
                                                </div>
                                            </div>
                                        </div>

                                        {{-- JABATAN --}}
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100 bg-white">
                                                <div class="text-muted mb-1">
                                                    <i class="fa fa-id-badge me-1"></i> Jabatan
                                                </div>
                                                <div class="fw-semibold">
                                                    {{ $pegawai['jabatan'] }}
                                                </div>
                                                <div class="mt-1">
                                                    @if(!is_null($pegawai['eselon']))
                                                        <span class="badge bg-info">
                                                            Eselon {{ $pegawai['eselon'] }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            Non Eselon
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- FLAG PUSDIKLATWAS --}}
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100 bg-white">
                                                <div class="text-muted mb-2">
                                                    <i class="fa fa-university me-1"></i> Pusdiklatwas
                                                </div>
                                                @if($pegawai['isPusdiklatwas'])
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-check me-1"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fa fa-times me-1"></i> Tidak
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- FLAG PUSBINJFA --}}
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100 bg-white">
                                                <div class="text-muted mb-2">
                                                    <i class="fa fa-users-cog me-1"></i> Pusbin JFA
                                                </div>
                                                @if($pegawai['isPusbinjfa'])
                                                    <span class="badge bg-success">
                                                        <i class="fa fa-check me-1"></i> Aktif
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fa fa-times me-1"></i> Tidak
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                    @if(isset($meta))
                        <div class="small text-muted">
                            Menampilkan
                            <strong>{{ ($meta['current_page'] - 1) * $meta['per_page'] + 1 }}</strong>
                            –
                            <strong>{{ min($meta['current_page'] * $meta['per_page'], $meta['total']) }}</strong>
                            dari
                            <strong>{{ $meta['total'] }}</strong>
                            data
                        </div>
                    @endif

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
                const targetSelector = button.getAttribute('data-bs-target')
                const target = document.querySelector(targetSelector)

                if (!target) return

                const icon = button.querySelector('i')

                target.addEventListener('show.bs.collapse', function () {
                    if (icon) {
                        icon.classList.remove('fa-plus')
                        icon.classList.add('fa-minus')
                    }
                })

                target.addEventListener('hide.bs.collapse', function () {
                    if (icon) {
                        icon.classList.remove('fa-minus')
                        icon.classList.add('fa-plus')
                    }
                })
            })

        })
    </script>
@endpush
