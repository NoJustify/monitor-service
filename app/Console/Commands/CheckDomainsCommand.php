<?php

namespace App\Console\Commands;

use App\Jobs\CheckDomainJob;
use App\Models\Domain;
use Illuminate\Console\Command;

class CheckDomainsCommand extends Command
{
    protected $signature = 'domains:check';

    protected $description = 'Check all active domains that need checking';

    public function handle(): int
    {
        $domains = Domain::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('last_checked_at')
                    ->orWhereRaw('last_checked_at <= NOW() - INTERVAL check_interval MINUTE');
            })
            ->get();

        $count = $domains->count();

        if ($count === 0) {
            $this->info('No domains to check.');
            return self::SUCCESS;
        }

        $this->info("Dispatching {$count} domain checks...");

        foreach ($domains as $domain) {
            CheckDomainJob::dispatch($domain);
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
