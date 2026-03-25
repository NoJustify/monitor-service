<?php

namespace App\Console\Commands;

use App\Models\Domain;
use Illuminate\Console\Command;

class CleanupChecksCommand extends Command
{
    protected $signature = 'checks:cleanup';

    protected $description = 'Remove old domain checks based on history_days setting';

    public function handle(): int
    {
        $domains = Domain::where('history_days', '>', 0)->get();
        $totalDeleted = 0;

        foreach ($domains as $domain) {
            $deleted = $domain->checks()
                ->where('created_at', '<', now()->subDays($domain->history_days))
                ->delete();

            $totalDeleted += $deleted;
        }

        $this->info("Deleted {$totalDeleted} old checks.");

        return self::SUCCESS;
    }
}
