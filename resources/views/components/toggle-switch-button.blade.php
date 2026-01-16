@if($isActive)
    {{-- NONAKTIF --}}
    <form id="deactivate-switch-{{ $userId }}"
          {{--action="{{ route('users.api.activate.ujikom', [$userId, $isValue]) }}"--}}
          action="{{ $isRoute }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('PATCH')

        <button type="button"
                class="btn btn-sm btn-outline-danger"
                title="Nonaktifkan {{ $isTitle }}"
                onclick="confirmAction(
                    'Nonaktifkan {{ $isTitle }}?',
                    '{{ $isTitle }} akan dinonaktifkan',
                    'warning',
                    'Ya, Nonaktifkan',
                    'deactivate-switch-{{ $userId }}'
                )">
            <i class="fa fa-ban"></i> Non Aktifkan {{ $isTitle }}
        </button>
    </form>
@else
    {{-- AKTIF --}}
    <form id="activate-switch-{{ $userId }}"
          {{--action="{{ route('users.api.activate.ujikom', [$userId, $isValue]) }}"--}}
          action="{{ $isRoute }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('PATCH')

        <button type="button"
                class="btn btn-sm btn-outline-success"
                title="Aktifkan {{ $isTitle }}"
                onclick="confirmAction(
                    'Aktifkan {{ $isTitle }}?',
                    '{{ $isTitle }} akan diaktifkan kembali',
                    'question',
                    'Ya, Aktifkan',
                    'activate-switch-{{ $userId }}'
                )">
            <i class="fa fa-check-circle"></i> Aktifkan {{ $isTitle }}
        </button>
    </form>
@endif
