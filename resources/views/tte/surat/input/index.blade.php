@extends('layouts.app')

@section('content')

    <style>
        /* === SELECT2 = BOOTSTRAP FORM-SELECT-SM === */
        .select2-container--default .select2-selection--single {
            min-height: calc(1.5em + .5rem + 2px); /* Bootstrap sm */
            height: calc(1.5em + .5rem + 2px);
            padding: .25rem .5rem;
            font-size: .875rem;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 0;
            padding-right: 0;
            line-height: normal;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }
    </style>

<div class="container-fluid px-3">

    @include('components.breadcrumb', ['breadcrumbs' => $breadcrumbs ?? []])

    <div class="card shadow-sm mt-3">

        <div class="card-header bg-white border-bottom">
            <div class="d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa fa-file-pen me-2 text-primary"></i>
                        List Surat Konsep
                    </h5>
                    {{--<small class="text-muted">
                        Data pengguna diambil dari API User
                    </small>--}}
                </div>

                {{-- TOMBOL TAMBAH --}}
                @can('menu.ttesurat.input.surat.add')
                    <a href="{{ route('input.step.satu') }}"
                       class="btn btn-sm btn-primary">
                        <i class="fa fa-plus me-1"></i>
                        Tambah Surat
                    </a>
                @endcan

            </div>
        </div>

        <div class="card-body">

            @if(isset($meta))
                <div class="mb-3">

                    {{-- BARIS 1 : SEARCH + TAHUN + CREATOR + BUTTON --}}
                    <form method="GET" class="mb-2">

                        <div class="d-flex align-items-center gap-2 flex-wrap">

                            {{-- SEARCH --}}
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control form-control-sm"
                                   placeholder="Cari surat..."
                                   style="width:220px">

                            {{-- FILTER TAHUN --}}
                            @php
                                $currentYear  = now()->year;
                                $selectedYear = request('year', $currentYear);
                            @endphp

                            <select name="year"
                                    onchange="this.form.submit()"
                                    class="form-select form-select-sm"
                                    style="width:140px">
                                <option value="">Semua Tahun</option>
                                @for($y = $currentYear; $y >= 2025; $y--)
                                    <option value="{{ $y }}" {{ (string)$selectedYear === (string)$y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>

                            {{-- FILTER CREATOR --}}
                            @role('superadmin')
                            <select name="creator"
                                    class="form-select form-select-sm select2">
                                <option value="">Semua Creator</option>
                                @foreach($pegawaiList as $pegawai)
                                    <option value="{{ $pegawai['pegawaiID'] }}"
                                        {{ request('creator') == $pegawai['pegawaiID'] ? 'selected' : '' }}>
                                        {{ $pegawai['pegawaiName'] }}
                                    </option>
                                @endforeach
                            </select>
                            @endrole

                            {{-- BUTTON SEARCH --}}
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-search"></i>
                            </button>

                        </div>

                        {{-- BARIS 2 : PER PAGE --}}
                        <div class="d-flex align-items-center gap-2 mt-2">
                            <span class="small text-muted">Tampilkan</span>

                            <select name="per_page"
                                    onchange="this.form.submit()"
                                    class="form-select form-select-sm"
                                    style="width:90px">
                                @foreach([10,25,50,100] as $size)
                                    <option value="{{ $size }}" {{ request('per_page',10) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </form>

                    {{-- PAGINATION --}}
                    <div class="d-flex justify-content-end">
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

                </div>
            @endif

            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                <tr class="text-center">
                    <th width="40"></th>
                    <th width="60">#</th>
                    <th style="width: 10%">Jenis Surat</th>
                    <th>Nomor & Nama Dokumen</th>
                    <th style="width: 15%">Upload</th>
                    <th style="width: 10%">Review s.d</th>
                    <th style="width: 120px">Aksi</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($konsepsurat as $konsepsurat)
                        <tr>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#detail-{{ $konsepsurat['id'] }}"
                                        data-collapse-type="detail"
                                        aria-expanded="false">
                                    <i id="icon-{{ $konsepsurat['id'] }}" class="fa fa-plus"></i>
                                </button>
                            </td>

                            <td class="text-center">
                                {{ (($meta['current_page'] - 1) * $meta['per_page']) + $loop->iteration }}
                            </td>
                            {{-- JENIS SURAT --}}
                            <td class="text-center">
                            <span class="badge bg-info text-dark">
                                {{ $konsepsurat['modul_surat']['nama'] ?? '-' }}
                            </span>
                            </td>
                            {{-- NOMOR & NAMA --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $konsepsurat['number'] ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $konsepsurat['name'] ?? 'Tanpa judul dokumen' }}
                                </div>
                            </td>

                            <td>
                                @if(!empty($konsepsurat['upload_date']))
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($konsepsurat['upload_date'])->format('d M Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($konsepsurat['upload_date'])->format('H:i') }}
                                        • {{ \Carbon\Carbon::parse($konsepsurat['upload_date'])->locale('id')->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">Belum ada</span>
                                @endif
                            </td>

                            <td class="text-center">
                                {!! titleEselonBadge($konsepsurat['reviu_last']) !!}
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">

                                    {{-- EDIT --}}
                                    @can('menu.ttesurat.input.surat.edit')
                                        <a href="{{ route('input.edit', $konsepsurat['id']) }}"
                                           class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center"
                                           style="width:32px;height:32px"
                                           title="Edit Surat">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    @endcan

                                    {{-- SOFT DELETE --}}
                                    @can('menu.ttesurat.input.surat.delete')
                                        @if(is_null($konsepsurat['deleted_at']))
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-warning"
                                                    onclick="softDelete('{{ $konsepsurat['id'] }}')"
                                                    title="Hapus (Soft)">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    @endcan

                                    {{-- FORCE DELETE --}}
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="forceDelete('{{ $konsepsurat['id'] }}')"
                                            title="Hapus Permanen">
                                        <i class="fa fa-skull-crossbones"></i>
                                    </button>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-success"
                                            onclick="restoreData('{{ $konsepsurat['id'] }}')"
                                            title="Restore">
                                        <i class="fa fa-undo"></i>
                                    </button>

                                    {{-- RESTORE --}}
                                    @if(!is_null($konsepsurat['deleted_at']))

                                    @endif

                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="10">
                                <div class="d-flex flex-wrap align-items-center gap-4">

                                    {{-- STATUS REVIEW --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-clipboard-check text-primary"></i>
                                        <span class="fw-semibold">Status:</span>
                                        @php
                                            $lastReview = $konsepsurat['latest_review'];
                                        @endphp

                                        @if ($lastReview)
                                            @php
                                                $name = $lastReview['reviews']['pegawaiName']
                                                    . ($lastReview['reviews']['eselon']
                                                        ? ' - ' . titleEselon($lastReview['reviews']['eselon'])
                                                        : '');
                                            @endphp

                                            {!! stateReviu(
                                                $lastReview['stat'],
                                                $lastReview['type'],
                                                $konsepsurat['stat'],
                                                $name
                                            ) !!}
                                        @else
                                            <span class="badge bg-light text-muted">Belum direview</span>
                                        @endif
                                    </div>

                                    {{-- PEMBUAT --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-user-edit text-info"></i>
                                        <span class="fw-semibold">Pembuat:</span>
                                        @if(!empty($konsepsurat['created_by']))
                                            <span>{{ $konsepsurat['created_by']['pegawaiName'] }}</span>
                                        @else
                                            <span class="text-muted fst-italic">Tidak diketahui</span>
                                        @endif
                                    </div>

                                    {{-- PENANDATANGAN --}}
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-user-check text-success"></i>
                                        <span class="fw-semibold">Penandatangan:</span>
                                        @if(!empty($konsepsurat['penandatangan']))
                                            <span>{{ $konsepsurat['penandatangan']['pegawaiName'] }}</span>
                                        @else
                                            <span class="text-muted fst-italic">Belum ditentukan</span>
                                        @endif
                                    </div>

                                </div>
                            </td>
                        </tr>
                        {{-- ROW DETAIL (COLLAPSE) --}}
                        <tr class="collapse bg-light" id="detail-{{ $konsepsurat['id'] }}">
                            <td colspan="10">

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            document
                .querySelectorAll('[data-bs-toggle="collapse"][data-collapse-type="detail"]')
                .forEach(button => {

                    const targetSelector = button.getAttribute('data-bs-target')
                    const target = document.querySelector(targetSelector)
                    if (!target) return

                    const icon = button.querySelector('i')
                    const iframe = target.querySelector('iframe[data-src]')

                    target.addEventListener('show.bs.collapse', function () {
                        if (icon) {
                            icon.classList.remove('fa-plus')
                            icon.classList.add('fa-minus')
                        }

                        if (iframe && !iframe.getAttribute('src')) {
                            iframe.setAttribute('src', iframe.dataset.src)
                        }
                    })

                    target.addEventListener('hide.bs.collapse', function () {
                        if (icon) {
                            icon.classList.remove('fa-minus')
                            icon.classList.add('fa-plus')
                        }

                        if (iframe) {
                            iframe.removeAttribute('src')
                        }
                    })
                })

        })
    </script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Pilih Creator',
                allowClear: true,
                width: '220px'
            });

            $('.select2').on('change', function () {
                $(this).closest('form').submit();
            });
        });
    </script>
    <script>
        function softDelete(id) {
            Swal.fire({
                title: 'Hapus Surat?',
                text: 'Surat akan dipindahkan ke trash',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm(
                        'DELETE',
                        "{{ route('input.destroy', ':id') }}".replace(':id', id)
                    );
                }
            });
        }

        function restoreData(id) {
            Swal.fire({
                title: 'Restore Surat?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Restore',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm(id, 'POST', '{{ url("tte/surat") }}/' + id + '/restore');
                }
            });
        }

        function forceDelete(id) {
            Swal.fire({
                title: 'HAPUS PERMANEN?',
                text: 'Data dan file akan hilang SELAMANYA!',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus permanen',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm(id, 'DELETE', '{{ url("tte/surat") }}/' + id + '/force');
                }
            });
        }

        function submitForm(method, action) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;

            form.innerHTML = `
        @csrf
            <input type="hidden" name="_method" value="${method}">
    `;

            document.body.appendChild(form);
            form.submit();
        }
    </script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
@endpush
