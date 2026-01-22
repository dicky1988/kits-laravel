{{-- TIMELINE HEADER --}}
<div class="fw-semibold mb-3 text-dark">
    <i class="fa fa-history me-1"></i>
    Riwayat Reviu & Persetujuan
</div>

{{-- TIMELINE --}}
<div class="timeline">

    {{-- INPUT DOKUMEN --}}
    <div class="timeline-item">
        <div class="timeline-dot bg-success"></div>

        <div class="timeline-content mb-2">
            <div class="d-flex align-items-start">

                {{-- FOTO PEMBUAT --}}
                @php
                    $fotoInput = 'https://map.bpkp.go.id/api/v1/dms/foto?niplama='
                        . ($ttesurat['created_by']['pegawaiID'] ?? '');
                @endphp

                <img
                    src="{{ $fotoInput }}"
                    width="36"
                    height="36"
                    class="rounded-circle border me-2"
                    onerror="this.onerror=null;this.src='{{ asset('images/avatar-default.png') }}';"
                >

                <div class="flex-grow-1">

                    <div class="fw-semibold mb-1 text-dark">
                        <i class="fa fa-plus-circle text-success me-1"></i>
                        Dokumen Diinput
                    </div>

                    <div class="small text-muted mb-1">
                        Oleh <strong>{{ $ttesurat['created_by']['pegawaiName'] ?? '-' }}</strong>
                    </div>

                    <span class="badge bg-light text-dark border mb-1">
                        <i class="fa fa-hashtag me-1"></i>
                        {{ $ttesurat['number'] ?? 'Nomor belum tersedia' }}
                    </span>

                    @if(!empty($ttesurat['created_at']))
                        <div class="small text-muted mt-1">
                            <i class="fa fa-clock me-1"></i>
                            {{ \Carbon\Carbon::parse($ttesurat['created_at'])->translatedFormat('d F Y H:i') }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- REVIEW --}}
    @foreach($ttesurat['reviews'] as $review)

        @php
            $fotoUrl = 'https://map.bpkp.go.id/api/v1/dms/foto?niplama=' . ($review['review_by'] ?? '');
            $reviewedAt = !empty($review['reviewed_at'])
                ? \Carbon\Carbon::parse($review['reviewed_at'])->translatedFormat('d F Y H:i')
                : null;

            $dotColor = match ($review['stat']) {
                1 => '#198754', // disetujui
                2 => '#dc3545', // ditolak
                default => '#ffc107', // menunggu
            };
        @endphp

        <div class="timeline-item">
            <div class="timeline-dot" style="background-color: {{ $dotColor }}"></div>

            <div class="timeline-content">
                <div class="d-flex align-items-start">

                    {{-- FOTO REVIEWER --}}
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
                                $review['stat'],
                                $review['type'],
                                $ttesurat['stat'],
                                $review['reviews']['pegawaiName'] ?? '',
                                $review['is_reject_to_conceptor'] ?? 0
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

    @endforeach

</div>
