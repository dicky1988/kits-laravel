@extends('layouts.app')

<style>
    .bg-light-hover:hover {
        background-color: #f8f9fa;
    }
    .cursor-pointer {
        cursor: pointer;
    }
</style>
<style>
    .timeline {
        position: relative;
        margin-left: 18px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 8px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 16px;
    }

    .timeline-dot {
        position: absolute;
        left: -2px;
        top: 6px;
        width: 10px;
        height: 10px;
        background: #0d6efd;
        border-radius: 50%;
    }

    .timeline-content {
        margin-left: 20px;
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
                            <i class="fa fa-envelope-open-text me-2 text-primary"></i>
                            List Monitoring Surat TTE
                        </h5>
                        {{--<small class="text-muted">
                            Data pengguna diambil dari API User
                        </small>--}}
                    </div>

                </div>
            </div>

            <div class="card-body">

                @if(isset($meta))
                    <div class="mb-3">

                        {{-- BARIS ATAS: FILTER --}}
                        <form method="GET" class="d-flex align-items-center gap-2 flex-wrap mb-3">

                            {{-- SEARCH --}}
                            <input type="text"
                                   name="search"
                                   value="{{ request('search') }}"
                                   class="form-control form-control-sm"
                                   placeholder="Cari surat..."
                                   style="width:200px">

                            {{-- FILTER TAHUN --}}
                            @php
                                $currentYear = now()->year;
                                $selectedYear = request('year', $currentYear);
                            @endphp

                            <select name="year"
                                    onchange="this.form.submit()"
                                    class="form-select form-select-sm w-auto">
                                <option value="">Semua Tahun</option>
                                @for($y = $currentYear; $y >= 2025; $y--)
                                    <option value="{{ $y }}"
                                        {{ (string)$selectedYear === (string)$y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>

                            {{-- PER PAGE --}}
                            <span class="small text-muted">Tampilkan</span>

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

                            {{-- BUTTON --}}
                            <button class="btn btn-sm btn-outline-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>

                        {{-- BARIS BAWAH: PAGINATION --}}
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
                        <th style="width: 10%">Penunggah</th>
                        <th style="width: 10%">Penandatangan</th>
                        <th>Status Review</th>
                        <th style="width: 10%">Review s.d</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($ttesurat as $ttesurat)
                        <tr>
                            <td class="text-center">
                                <button class="btn btn-sm btn-light"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#detail-{{ $ttesurat['id'] }}"
                                        aria-expanded="false">
                                    <i id="icon-{{ $ttesurat['id'] }}" class="fa fa-plus"></i>
                                </button>
                            </td>

                            <td class="text-center">
                                {{ (($meta['current_page'] - 1) * $meta['per_page']) + $loop->iteration }}
                            </td>
                            {{-- JENIS SURAT --}}
                            <td class="text-center">
                                <span class="badge bg-info text-dark">
                                    {{ $ttesurat['modul_surat']['nama'] ?? '-' }}
                                </span>
                            </td>
                            {{-- NOMOR & NAMA --}}
                            <td>
                                <div class="fw-semibold">
                                    {{ $ttesurat['number'] ?? '-' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $ttesurat['name'] ?? 'Tanpa judul dokumen' }}
                                </div>
                            </td>

                            <td>
                                @if(!empty($ttesurat['upload_date']))
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($ttesurat['upload_date'])->format('d M Y') }}
                                    </div>
                                    <div class="text-muted small">
                                        {{ \Carbon\Carbon::parse($ttesurat['upload_date'])->format('H:i') }}
                                        • {{ \Carbon\Carbon::parse($ttesurat['upload_date'])->locale('id')->diffForHumans() }}
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">Belum ada</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(!empty($ttesurat['created_by']))
                                    <div class="fw-semibold">
                                        {{ $ttesurat['created_by']['pegawaiName'] }}
                                    </div>
                                    {{--<div class="text-muted small">
                                        {{ format_nip($ttesurat['created_by']['pegawaiNIP'] ?? null) ?? 'NIP tidak tersedia' }}
                                    </div>--}}
                                @else
                                    <span class="text-muted fst-italic">Tidak diketahui</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if(!empty($ttesurat['penandatangan']))
                                    <div class="fw-semibold">
                                        {{ $ttesurat['penandatangan']['pegawaiName'] }}
                                    </div>
                                    {{--<div class="text-muted small">
                                        {{ format_nip($ttesurat['created_by']['pegawaiNIP'] ?? null) ?? 'NIP tidak tersedia' }}
                                    </div>--}}
                                @else
                                    <span class="text-muted fst-italic">Tidak diketahui</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @php
                                    $lastReview = $ttesurat['latest_review'];
                                @endphp

                                {{--{{ $lastReview->stat.'-'.$lastReview->type.'-'.$item->stat }}--}}
                                @if ($lastReview)
                                    @php
                                        if($lastReview['reviews']['eselon']) {
                                            $name = $lastReview['reviews']['pegawaiName'] . ' - ' . titleEselon($lastReview['reviews']['eselon']);
                                        } else {
                                            $name = $lastReview['reviews']['pegawaiName'];
                                        }
                                    @endphp
                                    {!! stateReviu(
                                        $lastReview['stat'],
                                        $lastReview['type'],
                                        $ttesurat['stat'],
                                        $name
                                    ) !!}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td class="text-center">
                                {!! titleEselonBadge($ttesurat['reviu_last']) !!}
                            </td>
                        </tr>
                        {{-- ROW DETAIL (COLLAPSE) --}}
                        <tr class="collapse bg-light" id="detail-{{ $ttesurat['id'] }}">
                            <td colspan="9">
                                @php
                                    $file = $ttesurat['files'] ?? null;
                                @endphp

                                @if($file)
                                    <div class="p-3">
                                        <div class="row g-3">

                                            {{-- PDF PREVIEW (8) --}}
                                            <div class="col-md-8">
                                                <div class="border rounded bg-white h-100">
                                                    <iframe
                                                        data-src="{{ route('arsip.preview', $file['id']) }}"
                                                        width="100%"
                                                        height="650"
                                                        style="border: none;"
                                                        loading="lazy">
                                                    </iframe>
                                                </div>

                                                <div class="text-muted small mt-2">
                                                    <i class="fa fa-info-circle me-1"></i>
                                                    Dokumen ditampilkan langsung dari arsip TTE (PDF Viewer)
                                                </div>
                                            </div>

                                            {{-- INFO CARD (4) --}}
                                            <div class="col-md-4">
                                                <div class="card shadow-sm h-100">
                                                    <div class="card-body">

                                                        {{-- HEADER --}}
                                                        <div class="fw-semibold mb-3 text-dark">
                                                            <i class="fa fa-file-pdf text-danger me-1"></i>
                                                            Informasi Dokumen
                                                        </div>

                                                        {{-- META DOKUMEN --}}
                                                        <div class="mb-2">
                                                            <div class="text-muted small">Nomor Surat</div>
                                                            <div class="fw-semibold">{{ $ttesurat['number'] ?? '-' }}</div>
                                                        </div>

                                                        <div class="mb-2">
                                                            <div class="text-muted small">Nama Dokumen</div>
                                                            <div class="fw-semibold">{{ $ttesurat['name'] ?? '-' }}</div>
                                                        </div>

                                                        <hr>

                                                        <div class="mb-2">
                                                            <div class="text-muted small">Nama File</div>
                                                            <div class="fw-semibold text-break">{{ $file['name'] }}</div>
                                                        </div>

                                                        @if(!empty($file['signed_at']))
                                                            <div class="mb-2">
                                                                <div class="text-muted small">Ditandatangani</div>
                                                                <div class="fw-semibold">
                                                                    {{ \Carbon\Carbon::parse($file['signed_at'])->translatedFormat('d F Y H:i') }}
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <hr>

                                                        {{-- TIMELINE HEADER --}}
                                                        <div class="fw-semibold mb-3 text-dark">
                                                            <i class="fa fa-history me-1"></i>
                                                            Riwayat Reviu & Persetujuan
                                                        </div>

                                                        {{-- TIMELINE --}}
                                                        <div class="timeline">

                                                            {{-- DOT --}}
                                                            <div class="timeline-dot bg-success"></div>

                                                            {{-- CONTENT --}}
                                                            <div class="timeline-content mb-2">
                                                                <div class="d-flex align-items-start">

                                                                    {{-- FOTO PEMBUAT --}}
                                                                    @php
                                                                        $fotoUrlInput = 'https://map.bpkp.go.id/api/v1/dms/foto?niplama='
                                                                            . ($ttesurat['created_by']['pegawaiID'] ?? '');
                                                                    @endphp

                                                                    <img
                                                                        src="{{ $fotoUrlInput }}"
                                                                        width="36"
                                                                        height="36"
                                                                        class="rounded-circle border me-2"
                                                                        onerror="this.onerror=null;this.src='{{ asset('images/avatar-default.png') }}';"
                                                                    >

                                                                    {{-- INFO --}}
                                                                    <div class="flex-grow-1">

                                                                        {{-- AKSI --}}
                                                                        <div class="fw-semibold text-dark mb-1">
                                                                            <i class="fa fa-plus-circle text-success me-1"></i>
                                                                            Dokumen Diinput
                                                                        </div>

                                                                        {{-- DETAIL --}}
                                                                        <div class="small text-muted mb-1">
                                                                            Oleh <strong>{{ $ttesurat['created_by']['pegawaiName'] ?? '-' }}</strong>
                                                                        </div>

                                                                        {{-- NOMOR SURAT --}}
                                                                        <div class="mb-1">
                                                                            <span class="badge bg-light text-dark border">
                                                                                <i class="fa fa-hashtag me-1"></i>
                                                                                {{ $ttesurat['number'] ?? 'Nomor belum tersedia' }}
                                                                            </span>
                                                                        </div>

                                                                        {{-- WAKTU --}}
                                                                        @php
                                                                            $inputAt = !empty($ttesurat['created_at'])
                                                                                ? \Carbon\Carbon::parse($ttesurat['created_at'])->translatedFormat('d F Y H:i')
                                                                                : null;
                                                                        @endphp

                                                                        @if($inputAt)
                                                                            <div class="small text-muted">
                                                                                <i class="fa fa-clock me-1"></i>
                                                                                {{ $inputAt }}
                                                                            </div>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            </div>

                                                            @foreach($ttesurat['reviews'] as $reviews)
                                                                @php
                                                                    $fotoUrl = 'https://map.bpkp.go.id/api/v1/dms/foto?niplama=' . ($reviews['review_by'] ?? '');
                                                                    $reviewedAt = $reviews['reviewed_at']
                                                                        ? \Carbon\Carbon::parse($reviews['reviewed_at'])->translatedFormat('d F Y H:i')
                                                                        : null;
                                                                @endphp

                                                                <div class="timeline-item">

                                                                    @php
                                                                        $dotColor = match ($reviews['stat']) {
                                                                            1 => '#198754', // disetujui
                                                                            2 => '#dc3545', // ditolak
                                                                            default => '#ffc107', // menunggu
                                                                        };
                                                                    @endphp
                                                                    <div class="timeline-dot" style="background-color: {{ $dotColor }}"></div>

                                                                    {{-- CONTENT --}}
                                                                    <div class="timeline-content">

                                                                        <div class="d-flex align-items-start">

                                                                            {{-- FOTO --}}
                                                                            <img
                                                                                src="{{ $fotoUrl }}"
                                                                                width="36"
                                                                                height="36"
                                                                                class="rounded-circle border me-2"
                                                                                onerror="this.onerror=null;this.src='{{ asset('images/avatar-default.png') }}';"
                                                                            >

                                                                            <div class="flex-grow-1">

                                                                                {{-- STATUS --}}
                                                                                <div class="mb-1">
                                                                                    {!! stateReviu(
                                                                                        $reviews['stat'],
                                                                                        $reviews['type'],
                                                                                        $ttesurat['stat'],
                                                                                        $reviews['reviews']['pegawaiName'] ?? '',
                                                                                        $reviews['is_reject_to_conceptor'] ?? 0
                                                                                    ) !!}
                                                                                </div>

                                                                                {{-- WAKTU --}}
                                                                                @if($reviewedAt)
                                                                                    <div class="small text-muted">
                                                                                        <i class="fa fa-clock me-1"></i>
                                                                                        {{ $reviewedAt }}
                                                                                    </div>
                                                                                @endif

                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                {{--{{ $reviews['is_reject_to_conceptor'].'-'.$reviews['id'] }}
                                                                {{ $reviews['stat'].'-'.$reviews['type'].'-'.$ttesurat['stat'].'-'.$reviews['reviews']['pegawaiName'] ?? ''.'-'.$reviews['review_number'] ?? null.'-' }}--}}
                                                            @endforeach

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @else
                                    {{-- EMPTY STATE --}}
                                    <div class="p-4 text-center text-muted">
                                        <i class="fa fa-file-pdf fa-3x mb-2 text-secondary"></i>
                                        <div class="fw-semibold">File PDF Belum Tersedia</div>
                                        <div class="small mt-1">
                                            Dokumen ini belum memiliki file hasil tanda tangan elektronik.
                                        </div>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
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
