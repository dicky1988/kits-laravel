<?php

namespace App\Console\Commands;

use App\Services\ModulSuratSyncService;
use Illuminate\Console\Command;
use App\Services\UserSyncService;

class SyncModulSurat extends Command
{
    protected $signature = 'user:sync-tte-modul';
    protected $description = 'Sinkron Modul Surat';

    public function handle()
    {
        $total = ModulSuratSyncService::syncByAll();
        $this->info("✔ Sinkronisasi selesai. Total modul surat diperbarui: {$total}");
    }
}
