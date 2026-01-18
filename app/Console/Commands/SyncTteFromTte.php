<?php

namespace App\Console\Commands;

use App\Services\PegawaiSyncService;
use App\Services\TteSyncService;
use Illuminate\Console\Command;

class SyncTteFromTte extends Command
{
    protected $signature = 'user:sync-tte';
    protected $description = 'Sinkron data TTE berdasarkan TTE';

    public function handle()
    {
        $total = TteSyncService::syncByAll();
        $this->info("✔ Sinkronisasi selesai. Total data TTE diperbarui: {$total}");
    }
}
