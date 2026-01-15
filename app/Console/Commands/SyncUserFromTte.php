<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\UserSyncService;

class SyncUserFromTte extends Command
{
    protected $signature = 'user:sync-tte';
    protected $description = 'Sinkron user berdasarkan nip_lama ↔ pegawai_id TTE';

    public function handle()
    {
        $total = UserSyncService::syncByNipLama();
        $this->info("✔ Sinkronisasi selesai. Total user diperbarui: {$total}");
    }
}
