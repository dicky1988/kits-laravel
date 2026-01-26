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
                <h5 class="mb-0 fw-semibold">
                    <i class="fa fa-file-pen me-2 text-primary"></i>
                    Input Surat TTE
                </h5>
            </div>

            <div class="card-body">

                <form id="formSurat"
                      action="{{ route('input.store.step.satu') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    {{-- JUDUL --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-pen me-1"></i> Judul Surat
                        </label>
                        <input type="text" name="name" class="form-control form-control-sm" required>
                    </div>

                    {{-- NOMOR --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-hashtag me-1"></i> Nomor Surat
                        </label>
                        <input type="text" name="number" class="form-control form-control-sm" required>
                    </div>

                    {{-- JENIS SURAT --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-layer-group me-1"></i> Jenis Surat
                        </label>

                        <select name="modul_surat_id"
                                class="form-select form-select-sm select2"
                                data-placeholder="-- Pilih Jenis Surat --"
                                required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            @foreach($jenisSuratList as $jenis)
                                <option value="{{ $jenis['id'] }}"
                                    {{ old('jenis_surat') == $jenis['id'] ? 'selected' : '' }}>
                                    {{ $jenis['nama'] }}
                                </option>
                            @endforeach
                        </select>

                        @error('jenis_surat')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FILE --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-file-arrow-up me-1"></i> File Surat (PDF / DOCX)
                        </label>
                        <input type="file" name="file_surat" class="form-control form-control-sm" accept=".pdf,.docx" required>
                    </div>

                    {{-- QR LOCATION --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-qrcode me-1"></i> Lokasi QR
                        </label>
                        <select name="qr_location_stat" class="form-control form-select-sm" id="inputQR" required>
                            <option value="2">Halaman Pertama</option>
                            <option value="3">Halaman Terakhir</option>
                            <option value="4">Tentukan Halaman (1 halaman)</option>
                        </select>
                    </div>

                    {{-- QR PAGE NUMBER (MUNCUL JIKA PILIH OPSI 4) --}}
                    <div class="mb-3 d-none" id="qrPageWrapper">
                        <label class="form-label">
                            <i class="fa fa-file-alt me-1"></i> Nomor Halaman QR
                        </label>
                        <input type="number"
                               name="qr_location_page"
                               class="form-control form-control-sm"
                               min="1"
                               placeholder="Contoh: 2">
                    </div>

                    {{-- TINGKAT REVIEW --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-user-check me-1"></i> Tingkat Review
                        </label>
                        <select name="tingkat_review" class="form-control form-select-sm" id="inputreview" required>
                            <option value="">-- Pilih --</option>
                            <option value="4">Reviu s.d Eselon IV</option>
                            <option value="3">Reviu s.d Eselon III</option>
                            <option value="2">Reviu s.d Eselon II</option>
                            <option value="1">Reviu s.d Eselon I</option>
                            <option value="0">Reviu s.d Kepala</option>
                            {{--@if(in_array(Auth::user()->role, [1, 99]))
                                <option value="99">Tanda Tangan Langsung</option>
                            @endif--}}
                        </select>
                    </div>

                    {{-- PEGAWAI ES IV --}}
                    <div class="mb-3 review-level" id="wrapper-es4">
                        <label class="form-label">
                            <i class="fa fa-user-tie me-1"></i> Pejabat Eselon IV
                        </label>

                        <select name="pegawai_es4"
                                class="form-select form-select-sm select2"
                                data-placeholder="-- Pilih Eselon IV --"
                                required>
                            <option value="">-- Pilih Eselon IV --</option>
                            @foreach($pegawaiEs4List as $es4)
                                <option value="{{ $es4['pegawaiID'] }}"
                                    {{ old('pegawai_es4') == $es4['pegawaiID'] ? 'selected' : '' }}>
                                    {{ $es4['pegawaiName'] }}&nbsp;&nbsp;-&nbsp;&nbsp;{{ $es4['jabatan'] }}
                                </option>
                            @endforeach
                        </select>

                        @error('pegawai_es4')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PEGAWAI ES III --}}
                    <div class="mb-3 d-none review-level" id="wrapper-es3">
                        <label class="form-label">
                            <i class="fa fa-user-tie me-1"></i> Pejabat Eselon III
                        </label>

                        <select name="pegawai_es3"
                                class="form-select form-select-sm select2"
                                data-placeholder="-- Pilih Eselon III --">
                            <option value="">-- Pilih Eselon III --</option>
                            @foreach($pegawaiEs3List as $es3)
                                <option value="{{ $es3['pegawaiID'] }}">
                                    {{ $es3['pegawaiName'] }} - {{ $es3['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PEGAWAI ES II --}}
                    <div class="mb-3 d-none review-level" id="wrapper-es2">
                        <label class="form-label">
                            <i class="fa fa-user-tie me-1"></i> Pejabat Eselon II
                        </label>

                        <select name="pegawai_es2"
                                class="form-select form-select-sm select2"
                                data-placeholder="-- Pilih Eselon II --">
                            <option value="">-- Pilih Eselon II --</option>
                            @foreach($pegawaiEs2List as $es2)
                                <option value="{{ $es2['pegawaiID'] }}">
                                    {{ $es2['pegawaiName'] }} - {{ $es2['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PEGAWAI ES I --}}
                    <div class="mb-3 d-none review-level" id="wrapper-es1">
                        <label class="form-label">
                            <i class="fa fa-user-tie me-1"></i> Pejabat Eselon I
                        </label>

                        <select name="pegawai_es1"
                                class="form-select form-select-sm select2"
                                data-placeholder="-- Pilih Eselon I --">
                            <option value="">-- Pilih Eselon I --</option>
                            @foreach($pegawaiEs1List as $es1)
                                <option value="{{ $es1['pegawaiID'] }}">
                                    {{ $es1['pegawaiName'] }} - {{ $es1['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PEGAWAI KEPALA (ESELON 0) --}}
                    <div class="mb-3 d-none review-level" id="wrapper-es0">
                        <label class="form-label">
                            <i class="fa fa-user-tie me-1"></i> Kepala
                        </label>

                        <select name="pegawai_es0"
                                class="form-select form-select-sm select2"
                                data-placeholder="-- Pilih Kepala --">
                            <option value="">-- Pilih Kepala --</option>
                            @foreach($pegawaiEs0List as $es0)
                                <option value="{{ $es0['pegawaiID'] }}">
                                    {{ $es0['pegawaiName'] }} - {{ $es0['jabatan'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- PENANDATANGAN --}}
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fa fa-user-tie me-1"></i> Pejabat Penandatangan
                        </label>

                        <select name="pegawai_penandatangan"
                                class="form-select form-select-sm select2"
                                data-placeholder="-- Pilih Pejabat Penandatangan --"
                                required>
                            <option value="">-- Pilih Pejabat Penandatangan --</option>
                            @foreach($pegawaiPenandatangan as $penandatangan)
                                <option value="{{ $penandatangan['pegawaiID'] }}"
                                    {{ old('pegawai_penandatangan') == $penandatangan['pegawaiID'] ? 'selected' : '' }}>
                                    {{ $penandatangan['pegawaiName'] }}&nbsp;&nbsp;-&nbsp;&nbsp;{{ $penandatangan['jabatan'] }}
                                </option>
                            @endforeach
                        </select>

                        @error('pegawai_penandatangan')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="button"
                            class="btn btn-primary"
                            id="btnSubmit">
                        <i class="fa fa-save me-1"></i> Simpan Draft
                    </button>

                </form>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif
    <script>
        $(document).ready(function () {

            $('.select2').each(function () {
                const placeholder = $(this).data('placeholder') || 'Pilih data';

                $(this).select2({
                    width: '100%',
                    placeholder: placeholder,
                    allowClear: true
                });
            });

            // === QR PAGE TOGGLE ===
            const $qrSelect = $('#inputQR');
            const $qrPageWrapper = $('#qrPageWrapper');
            const $qrPageInput = $('input[name="qr_location_page"]');

            function toggleQrPage() {
                if ($qrSelect.val() === '4') {
                    $qrPageWrapper.removeClass('d-none');
                    $qrPageInput.prop('required', true);
                } else {
                    $qrPageWrapper.addClass('d-none');
                    $qrPageInput.prop('required', false).val('');
                }
            }

            $qrSelect.on('change', toggleQrPage);
            toggleQrPage(); // untuk old value

            // === REVIEW LEVEL TOGGLE ===
            const $reviewSelect = $('#inputreview');

            function toggleReviewLevel() {
                const level = parseInt($reviewSelect.val());

                // reset semua field pejabat
                $('.review-level')
                    .addClass('d-none')
                    .find('select')
                    .prop('required', false)
                    .val('')
                    .trigger('change');

                // TTD langsung
                if (level === 99 || isNaN(level)) {
                    return;
                }

                // Eselon IV selalu muncul jika level <= 4
                $('#wrapper-es4').removeClass('d-none')
                    .find('select').prop('required', true);

                if (level <= 3) {
                    $('#wrapper-es3').removeClass('d-none')
                        .find('select').prop('required', true);
                }

                if (level <= 2) {
                    $('#wrapper-es2').removeClass('d-none')
                        .find('select').prop('required', true);
                }

                if (level <= 1) {
                    $('#wrapper-es1').removeClass('d-none')
                        .find('select').prop('required', true);
                }

                if (level === 0) {
                    $('#wrapper-es0').removeClass('d-none')
                        .find('select').prop('required', true);
                }
            }

            $reviewSelect.on('change', toggleReviewLevel);
            toggleReviewLevel(); // handle old value
        });
    </script>
    <script>
        const btnSubmit = document.getElementById('btnSubmit');
        const formSurat = document.getElementById('formSurat');

        btnSubmit.addEventListener('click', function () {

            // =====================
            // CEK VALIDASI HTML
            // =====================
            if (!formSurat.checkValidity()) {
                formSurat.reportValidity(); // tampilkan pesan browser
                return;
            }

            Swal.fire({
                title: 'Simpan Surat?',
                text: 'Surat akan disimpan sebagai draft TTE',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#0d6efd'
            }).then((result) => {

                if (!result.isConfirmed) return;

                // =====================
                // DISABLE BUTTON
                // =====================
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Menyimpan...
        `;

                // =====================
                // LOADING ALERT
                // =====================
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                formSurat.submit();
            });
        });
    </script>
@endpush
