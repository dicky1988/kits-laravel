@if($isActive)
    {{-- NONAKTIF --}}
    <form id="deactivate-sync-{{ $userId }}"
          action="{{ route('users.api.deactivate.sync', $userId) }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('PATCH')

        <button type="button"
                class="btn btn-sm btn-outline-danger"
                title="Nonaktifkan User Sync"
                onclick="confirmAction(
                    'Nonaktifkan User Sync?',
                    'User sync ini akan dinonaktifkan',
                    'warning',
                    'Ya, Nonaktifkan',
                    'deactivate-sync-{{ $userId }}'
                )">
            <i class="fas fa-sync-alt"></i>
        </button>
    </form>
@else
    {{-- AKTIF --}}
    <form id="activate-sync-{{ $userId }}"
          action="{{ route('users.api.activate.sync', $userId) }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('PATCH')

        <button type="button"
                class="btn btn-sm btn-outline-success"
                title="Aktifkan User Sync"
                onclick="confirmAction(
                    'Aktifkan User Sync?',
                    'User sync ini akan diaktifkan kembali',
                    'question',
                    'Ya, Aktifkan',
                    'activate-sync-{{ $userId }}'
                )">
            <i class="fas fa-sync-alt"></i>
        </button>
    </form>
@endif
