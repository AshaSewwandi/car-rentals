<?php

namespace App\Console\Commands;

use App\Services\DagpsSyncService;
use Illuminate\Console\Command;
use Throwable;

class DagpsSyncCommand extends Command
{
    protected $signature = 'dagps:sync {--dashboard-url=}';
    protected $description = 'Pull latest tracking points from DAGPS and update cars.';

    public function handle(DagpsSyncService $syncService): int
    {
        try {
            $result = $syncService->sync($this->option('dashboard-url'));
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info('DAGPS sync complete.');
        $this->line('Devices seen: '.$result['devices_seen']);
        $this->line('Cars updated: '.$result['cars_updated']);

        return self::SUCCESS;
    }
}
