<?php

namespace App\Console\Commands;

use App\Services\PegawaiSyncService;
use Illuminate\Console\Command;

class SyncPegawaiFromTte extends Command
{
    protected $signature = 'user:sync-tte';
    protected $description = 'Sinkron pegawai berdasarkan nip_lama ↔ pegawai_id TTE';

    public function handle()
    {
        $total = PegawaiSyncService::syncByAll();
        $this->info("✔ Sinkronisasi selesai. Total pegawai diperbarui: {$total}");
    }
}
