@if($isActive)
    {{-- NONAKTIF --}}
    <form id="deactivate-{{ $userId }}"
          action="{{ route('users.api.deactivate', $userId) }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('PATCH')

        <button type="button"
                class="btn btn-sm btn-outline-danger"
                title="Nonaktifkan User"
                onclick="confirmAction(
                    'Nonaktifkan User?',
                    'User ini akan dinonaktifkan',
                    'warning',
                    'Ya, Nonaktifkan',
                    'deactivate-{{ $userId }}'
                )">
            <i class="fa fa-user-slash"></i>
        </button>
    </form>
@else
    {{-- AKTIF --}}
    <form id="activate-{{ $userId }}"
          action="{{ route('users.api.activate', $userId) }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('PATCH')

        <button type="button"
                class="btn btn-sm btn-outline-success"
                title="Aktifkan User"
                onclick="confirmAction(
                    'Aktifkan User?',
                    'User ini akan diaktifkan kembali',
                    'question',
                    'Ya, Aktifkan',
                    'activate-{{ $userId }}'
                )">
            <i class="fa fa-user-check"></i>
        </button>
    </form>
@endif
