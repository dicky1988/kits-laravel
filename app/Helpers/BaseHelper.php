<?php
if (! function_exists('stateReviu')) {

    function stateReviu($state, $type, $stat_surat, $name = '')
    {
        $nameText = $name ? ' - ' . e($name) : '';

        // ===============================
        // REVIEW (TYPE 1)
        // ===============================
        if ($type == 1) {

            if ($state === 0 && in_array($stat_surat, [0, 1])) {
                return badge('warning', 'Menunggu Review' . $nameText);
            }

            if ($state === 1 && $stat_surat == 0) {
                return badge('success', 'Review Disetujui' . $nameText);
            }

            if ($state === 2 && $stat_surat == 0) {
                return badge('danger', 'Review Dikembalikan' . $nameText);
            }

            if ($state === 0 && $stat_surat == 2) {
                return badge('danger', 'Review Dikembalikan ke' . $nameText);
            }

            if ($state === 1 && $stat_surat == 3) {
                return badge('info', 'Menunggu Penomoran');
            }

            if ($state === 0 && $stat_surat == 3) {
                return badge('info', 'Menunggu Penomoran dan Lanjut di Reviu Eselon I / Kepala');
            }
        }

        // ===============================
        // PENOMORAN (TYPE 2)
        // ===============================
        if ($type == 2) {

            if ($state === 0 && $stat_surat == 0) {
                return badge('info', 'Menunggu Penomoran' . $nameText);
            }

            if ($state === 1 && $stat_surat == 0) {
                return badge('success', 'Penomoran Selesai' . $nameText);
            }
        }

        // ===============================
        // TANDA TANGAN (TYPE 3)
        // ===============================
        if ($type == 3) {

            if ($state === 0 && $stat_surat == 4) {
                return badge('info', 'Menunggu Tanda Tangan' . $nameText);
            }

            if ($state === 1 && $stat_surat == 4) {
                return badge('info', 'Menunggu Tanda Tangan' . $nameText);
            }

            if ($state === 1 && $stat_surat == 1) {
                return badge('info', 'Penomoran Tanda Tangan' . $nameText);
            }

            if ($state === 1 && $stat_surat == 5) {
                return badge('success', 'Selesai' . $nameText);
            }
        }

        // ===============================
        // DEFAULT
        // ===============================
        return badge('secondary', 'Status Tidak Diketahui');
    }
}

if (!function_exists('titleEselon')) {
    function titleEselon($eselon)
    {
        if($eselon == 1) {
            return 'Es I';
        }
        if($eselon == 2) {
            return 'Es II';
        }
        if($eselon == 3) {
            return 'Es III';
        }
        if($eselon == 4) {
            return 'Es IV';
        }
    }
}

if (! function_exists('titleEselonBadge')) {

    function titleEselonBadge($reviuLast)
    {
        switch ($reviuLast) {
            case 1:
                return badge('secondary', 'Konseptor');

            case 2:
                return badge('info', 'Eselon IV');

            case 3:
                return badge('info', 'Eselon III');

            case 4:
                return badge('primary', 'Eselon II');

            case 5:
                return badge('primary', 'Eselon I / Kepala');

            default:
                return badge('light', 'NO-DATA');
        }
    }
}

if (! function_exists('badge')) {
    function badge($type, $text)
    {
        return '<span class="badge rounded-pill bg-' . $type . '">' . $text . '</span>';
    }
}
