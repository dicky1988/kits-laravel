<?php
if (! function_exists('stateReviu1')) {

    function stateReviu1($state, $type, $stat_surat, $name = '')
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

            if ($state === 1 && $stat_surat == 5) {
                return badge('success', 'Selesai' . $nameText);
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

            if ($state === 1 && $stat_surat == 5) {
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

if (! function_exists('stateReviu')) {

    function stateReviu($state, $type, $stat_surat, $name = '',$tokonseptor = 0)
    {
        $nameText = $name ? ' - ' . e($name) : '';

        if($stat_surat == 0) {
            if($type == 1) {
                if($state == 1) {
                    return badge('success', 'Selesai Review' . $nameText);
                } elseif($state == 0) {
                    return badge('warning', 'Menunggu Review' . $nameText);
                } else {
                    return badge('secondary', 'Status Tidak Diketahui');
                }
            } elseif ($type == 2) {

            } elseif ($type == 3) {

            } else {
                return badge('secondary', 'Status Tidak Diketahui');
            }
        } elseif ($stat_surat == 1) {
            if($type == 1) {
                if($state == 1) {
                    return badge('success', 'Selesai Review' . $nameText);
                } elseif($state == 0) {
                    return badge('warning', 'Menunggu Review' . $nameText);
                } else {
                    return badge('secondary', 'Status Tidak Diketahui');
                }
            } elseif ($type == 2) {

            } elseif ($type == 3) {

            } else {
                return badge('secondary', 'Status Tidak Diketahui');
            }
        } elseif ($stat_surat == 2) {
            if($type == 1) {
                if($state == 1) {
                    return badge('success', 'Selesai Review' . $nameText);
                } elseif($state == 0) {
                    return badge('warning', 'Menunggu Perbaikan' . $nameText);
                } elseif($state == 2) {
                    return badge('danger', 'Dikembalikan' . $nameText);
                } else {
                    return badge('secondary', 'Status Tidak Diketahui');
                }
            } elseif ($type == 2) {

            } elseif ($type == 3) {

            } else {
                return badge('secondary', 'Status Tidak Diketahui');
            }
        } elseif ($stat_surat == 3) {
            if($type == 1) {

            } elseif ($type == 2) {

            } elseif ($type == 3) {

            } else {
                return badge('secondary', 'Status Tidak Diketahui');
            }
        } elseif ($stat_surat == 4) {
            if($type == 1) {
                if($state == 1) {
                    return badge('success', 'Selesai Review' . $nameText);
                } elseif($state == 0) {
                    return badge('warning', 'Menunggu Review' . $nameText);
                } else {
                    return badge('secondary', 'Status Tidak Diketahui');
                }
            } elseif ($type == 2) {
                if($state == 1) {
                    return badge('info', 'Selesai Penomoran' . $nameText);
                } elseif($state == 0) {
                    return badge('warning', 'Menunggu Penomoran' . $nameText);
                } else {
                    return badge('secondary', 'Status Tidak Diketahui');
                }
            } elseif ($type == 3) {
                if($state == 1) {
                    return badge('primary', 'Selesai di TTE' . $nameText);
                } elseif($state == 0) {
                    return badge('warning', 'Menunggu di TTE' . $nameText);
                } else {
                    return badge('secondary', 'Status Tidak Diketahui');
                }
            } else {
                return badge('secondary', 'Status Tidak Diketahui');
            }
        } elseif ($stat_surat == 5) {
            if($type == 1) {
                if($state == 1) {
                    return badge('success', 'Selesai Review' . $nameText);
                } elseif($state == 0) {

                } else {
                    return badge('secondary', 'Status Tidak Diketahui');
                }
            } elseif ($type == 2) {
                if($state == 1) {
                    return badge('warning', 'Selesai Penomoran' . $nameText);
                } elseif ($state == 0) {
                    return badge('info', 'Menununggu Penomoran' . $nameText);
                } else {

                }
            } elseif ($type == 3) {
                if($state == 1) {
                    return badge('primary', 'Selesai Ditandatangani' . $nameText);
                } elseif ($state == 0) {
                    return badge('info', 'Menununggu Ditandatangani' . $nameText);
                } else {

                }
            } else {
                return badge('secondary', 'Status Tidak Diketahui');
            }
        } else {
            return badge('secondary', 'Status Tidak Diketahui');
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
                return badge('secondary', 'Eselon I');

            case 2:
                return badge('info', 'Eselon II');

            case 3:
                return badge('info', 'Eselon III');

            case 4:
                return badge('primary', 'Eselon IV');

            case 0:
                return badge('primary', 'Kepala');

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
