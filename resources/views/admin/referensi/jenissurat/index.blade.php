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
                            List Jenis Surat
                        </h5>
                        {{--<small class="text-muted">
                            Data pengguna diambil dari API User
                        </small>--}}
                    </div>

                    <button class="btn btn-sm btn-primary"
                            onclick="openCreateModal()">
                        <i class="fa fa-plus me-1"></i>
                        Tambah Jenis Surat
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
                                   placeholder="Cari jenis surat..."
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
                        <th width="60" class="text-center">#</th>
                        <th class="text-center">Nama</th>
                        <th width="60" class="text-center">Ikon</th>
                        <th width="60" class="text-center">Warna</th>
                        <th style="width: 10%" class="text-center">Status</th>
                        <th width="120" class="text-center">Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($jenissurat as $jenissurat)
                        <tr>
                            <td class="text-center">
                                {{ (($meta['current_page'] - 1) * $meta['per_page']) + $loop->iteration }}
                            </td>
                            <td>{{ $jenissurat['nama'] }}</td>
                            <td class="text-center"><i class="fa fa-{!! $jenissurat['icon']  !!} me-2 text-primary"></i></td>
                            <td class="text-center">
                                <span
                                    class="d-inline-block rounded-circle border"
                                    style="width:18px;height:18px;background-color: {{ $jenissurat['color'] }};"
                                    title="{{ $jenissurat['color'] }}">
                                </span>
                            </td>
                            <td class="text-center">
                                {{--@if($jenissurat['is_aktif'])
                                    <span class="badge bg-success">
                                        <i class="fa fa-check-circle me-1"></i> Aktif
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        <i class="fa fa-times-circle me-1"></i> Tidak Aktif
                                    </span>
                                @endif--}}

                                    <x-toggle-switch-button
                                        :user-id="$jenissurat['id']"
                                        :is-active="($jenissurat['is_aktif'] ?? 1) == 1"
                                        :is-value="($jenissurat['is_aktif'] ?? 1) == 1"
                                        is-route="{{ route('users.api.activate.ujikom', [
                                                    $jenissurat['id'],$jenissurat['is_aktif']
                                                ]) }}"
                                        :is-title="''"
                                    />

                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-warning me-1"
                                        onclick="openEditModal({{ json_encode($jenissurat) }})">
                                    <i class="fa fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDelete({{ $jenissurat['id'] }}, '{{ $jenissurat['nama'] }}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
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

    <div class="modal fade" id="modalJenisSurat" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        Tambah Jenis Surat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="formJenisSurat">
                    @csrf
                    <input type="hidden" id="jenis_id">

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Nama Jenis Surat</label>
                            <input type="text" id="nama" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Icon (FontAwesome)</label>
                            <input type="text" id="icon" class="form-control" placeholder="file-alt">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Warna</label>
                            <input type="color" id="color" class="form-control form-control-color">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button class="btn btn-primary" type="submit">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const modal = new bootstrap.Modal(document.getElementById('modalJenisSurat'));
        let isEdit = false;
        let allowSubmit = true;

        document.getElementById('modalJenisSurat')
            .addEventListener('hidden.bs.modal', function () {
                allowSubmit = false;
                isEdit = false;
                document.getElementById('formJenisSurat').reset();
                document.getElementById('jenis_id').value = '';
            });

        function openCreateModal() {
            allowSubmit = true;
            isEdit = false;
            document.getElementById('modalTitle').innerText = 'Tambah Jenis Surat';
            document.getElementById('formJenisSurat').reset();
            document.getElementById('jenis_id').value = '';
            modal.show();
        }

        function openEditModal(data) {
            allowSubmit = true;
            isEdit = true;
            document.getElementById('modalTitle').innerText = 'Edit Jenis Surat';

            document.getElementById('jenis_id').value = data.id;
            document.getElementById('nama').value = data.nama ?? '';
            document.getElementById('icon').value = data.icon ?? '';
            document.getElementById('color').value = data.color ?? '#000000';

            modal.show();
        }

        document.getElementById('formJenisSurat').addEventListener('submit', function (e) {
            if (!allowSubmit) return;

            allowSubmit = true;
            e.preventDefault();

            const id = document.getElementById('jenis_id').value;

            const url = isEdit
                ? `{{ url('modulsurat') }}/${id}`
                : `{{ route('modulsurat.store') }}`;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    _method: isEdit ? 'PUT' : 'POST',
                    nama: document.getElementById('nama').value,
                    icon: document.getElementById('icon').value,
                    color: document.getElementById('color').value
                })
            })
                .then(async res => {
                    if (!res.ok) {
                        const err = await res.json();
                        throw new Error(err.message ?? 'Gagal menyimpan data');
                    }
                    return res.json();
                })
                .then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: isEdit ? 'Data berhasil diperbarui' : 'Data berhasil disimpan',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                })
                .catch(err => {
                    Swal.fire('Error', err.message, 'error');
                });
        });

        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Data?',
                text: `Jenis surat "${nama}" akan dihapus`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`{{ url('modulsurat') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'DELETE'
                        })
                    })
                        .then(res => {
                            if (!res.ok) throw new Error('Gagal menghapus data');
                            return res.json();
                        })
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus',
                                text: 'Data berhasil dihapus',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => location.reload());
                        })
                        .catch(err => {
                            Swal.fire('Error', err.message, 'error');
                        });
                }
            });
        }
    </script>
@endpush
