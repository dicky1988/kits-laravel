@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3">

        @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs ?? []])

        <div class="card shadow-sm mt-3">

            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-0 fw-semibold">
                            <i class="fa fa-users me-2 text-primary"></i>
                            List Users
                        </h5>
                        {{--<small class="text-muted">
                            Data pengguna diambil dari API User
                        </small>--}}
                    </div>

                    <button type="button"
                            class="btn btn-sm btn-primary"
                            onclick="confirmSync()">
                        <i class="fa fa-sync me-1"></i>
                        Sinkron TTE
                    </button>

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
                                   placeholder="Cari nama..."
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
                        <th width="60">#</th>

                        <th class="text-start text-center">
                            <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'name',
                                    'direction' =>
                                        ($sort === 'name' && $direction === 'asc') ? 'desc' : 'asc'
                                ]) }}" class="text-decoration-none text-dark">
                                Nama
                                @if($sort === 'name')
                                    {!! $direction === 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        <th>Email</th>
                        <th>NIP</th>

                        {{--<th width="180">Tanggal Daftar</th>--}}
                        <th width="180" class="text-start text-center">
                            <a href="{{ request()->fullUrlWithQuery([
                                    'sort' => 'created_at',
                                    'direction' =>
                                        ($sort === 'created_at' && $direction === 'asc') ? 'desc' : 'asc'
                                ]) }}" class="text-decoration-none text-dark">
                                Tanggal Daftar
                                @if($sort === 'created_at')
                                    {!! $direction === 'asc' ? '▲' : '▼' !!}
                                @endif
                            </a>
                        </th>

                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($users as $user)
                        <tr class="{{ ($user['is_aktif'] ?? 1) == 0 ? 'table-danger text-muted' : '' }}">

                            <td class="text-center">
                                <button class="btn btn-sm btn-light"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#detail-{{ $user['id'] }}"
                                        aria-expanded="false">
                                    <i id="icon-{{ $user['id'] }}" class="fa fa-plus"></i>
                                </button>
                            </td>

                            <td class="text-center">
                                {{ (($meta['current_page'] - 1) * $meta['per_page']) + $loop->iteration }}
                            </td>
                            <td>{{ $user['name'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ format_nip($user['nip']) }}</td>
                            <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y H:i:s') }}</td>

                            <td class="text-center">
                                <x-toggle-user-status-button
                                    :user-id="$user['id']"
                                    :is-active="($user['is_aktif'] ?? 1) == 1"
                                />

                                <x-toggle-user-sync-button
                                    :user-id="$user['id']"
                                    :is-active="($user['is_sync'] ?? 1) == 1"
                                />
                            </td>
                        </tr>

                        {{-- ROW DETAIL (COLLAPSE) --}}
                        <tr class="collapse bg-light" id="detail-{{ $user['id'] }}">
                            <td colspan="8">
                                <div class="p-3">
                                    <div class="row g-3">

                                        {{-- STATUS USER --}}
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100 bg-white shadow-sm">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <div class="text-muted small">Status User</div>
                                                        {!! ($user['is_aktif'] ?? 1)
                                                            ? '<span class="badge bg-success fs-6 mt-1">
                                                                    <i class="fa fa-user-check me-1"></i> Aktif
                                                               </span>'
                                                            : '<span class="badge bg-danger fs-6 mt-1">
                                                                    <i class="fa fa-user-slash me-1"></i> Nonaktif
                                                               </span>' !!}
                                                    </div>
                                                    <i class="fa fa-user fa-2x text-muted opacity-25"></i>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- STATUS SYNC --}}
                                        <div class="col-md-6">
                                            <div class="border rounded p-3 h-100 bg-white shadow-sm">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <div class="text-muted small">Sinkronisasi TTE</div>
                                                        {!! ($user['is_sync'] ?? 1)
                                                            ? '<span class="badge bg-primary fs-6 mt-1">
                                                                    <i class="fa fa-sync-alt me-1"></i> Aktif
                                                               </span>'
                                                            : '<span class="badge bg-secondary fs-6 mt-1">
                                                                    <i class="fa fa-ban me-1"></i> Nonaktif
                                                               </span>' !!}
                                                    </div>
                                                    <i class="fa fa-sync fa-2x text-muted opacity-25"></i>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
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

        <form id="sync-form"
              action="{{ route('users.sync.tte') }}"
              method="POST"
              class="d-none">
            @csrf
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        function confirmSync() {
            Swal.fire({
                title: 'Yakin ingin sinkron data?',
                text: 'Data user akan disesuaikan dengan sistem TTE',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Sinkron',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sedang sinkron...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });
                    document.getElementById('sync-form').submit();
                }
            });
        }
    </script>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    <script>
        function confirmAction(title, text, icon, confirmText, formId) {

            const form = document.getElementById(formId);

            if (!form) {
                console.error('Form tidak ditemukan:', formId);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Form tidak ditemukan'
                });
                return;
            }

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {

                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    form.submit();
                }
            });
        }
    </script>
    <script>
        function toggleIcon(id) {
            const icon = document.getElementById('icon-' + id);
            const collapse = document.getElementById('detail-' + id);

            if (!icon || !collapse) return;

            setTimeout(() => {
                if (collapse.classList.contains('show')) {
                    icon.classList.remove('fa-plus');
                    icon.classList.add('fa-minus');
                } else {
                    icon.classList.remove('fa-minus');
                    icon.classList.add('fa-plus');
                }
            }, 150);
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document.querySelectorAll('.collapse').forEach(function (collapseEl) {

                collapseEl.addEventListener('shown.bs.collapse', function () {
                    const id = collapseEl.id.replace('detail-', '');
                    const icon = document.getElementById('icon-' + id);

                    if (icon) {
                        icon.classList.remove('fa-plus');
                        icon.classList.add('fa-minus');
                    }
                });

                collapseEl.addEventListener('hidden.bs.collapse', function () {
                    const id = collapseEl.id.replace('detail-', '');
                    const icon = document.getElementById('icon-' + id);

                    if (icon) {
                        icon.classList.remove('fa-minus');
                        icon.classList.add('fa-plus');
                    }
                });

            });

        });
    </script>
@endpush
