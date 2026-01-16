@if(!empty($breadcrumbs))
    <nav aria-label="breadcrumb" class="mb-3 mt-3">
        <ol class="breadcrumb bg-white px-3 py-2 rounded shadow-sm align-items-center">
            @foreach($breadcrumbs as $breadcrumb)
                @if(!empty($breadcrumb['url']))
                    <li class="breadcrumb-item">
                        <a href="{{ $breadcrumb['url'] }}"
                           class="text-decoration-none text-primary fw-semibold">
                            {{ $breadcrumb['title'] }}
                        </a>
                    </li>
                @else
                    <li class="breadcrumb-item active fw-semibold text-dark"
                        aria-current="page">
                        {{ $breadcrumb['title'] }}
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
