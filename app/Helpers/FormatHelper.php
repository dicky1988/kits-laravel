<?php

if (! function_exists('format_nip')) {
    function format_nip(?string $nip): string
    {
        if (! $nip || strlen($nip) !== 18) {
            return $nip ?? '-';
        }

        return sprintf(
            '%s %s %s %s',
            substr($nip, 0, 8),   // tanggal lahir
            substr($nip, 8, 6),   // tahun & bulan pengangkatan
            substr($nip, 14, 1),  // jenis kelamin
            substr($nip, 15, 3)   // nomor urut
        );
    }
}
