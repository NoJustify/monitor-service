<?php

namespace App\Jobs;

use App\Models\Domain;
use App\Models\DomainCheck;
use App\Notifications\DomainDownNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CheckDomainJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Domain $domain
    ) {}

    public function handle(): void
    {
        $startTime = microtime(true);
        $isSuccess = false;
        $statusCode = null;
        $error = null;
        $contentMatched = null;

        try {
            $response = Http::timeout($this->domain->timeout)
                ->withoutVerifying()
                ->{strtolower($this->domain->http_method->value)}($this->domain->url);

            $statusCode = $response->status();
            $isSuccess = $response->successful();

            if ($isSuccess && $this->domain->expected_content) {
                $contentMatched = str_contains($response->body(), $this->domain->expected_content);
                $isSuccess = $contentMatched;
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $responseTime = (int) ((microtime(true) - $startTime) * 1000);

        /** @var DomainCheck $check */
        $check = $this->domain->checks()->create([
            'status_code' => $statusCode,
            'response_time' => $responseTime,
            'is_success' => $isSuccess,
            'content_matched' => $contentMatched,
            'error' => $error,
            'created_at' => now(),
        ]);

        Log::channel('checks')->info('Domain check', [
            'domain_id' => $this->domain->id,
            'url' => $this->domain->url,
            'status_code' => $statusCode,
            'response_time' => $responseTime,
            'is_success' => $isSuccess,
            'content_matched' => $contentMatched,
            'error' => $error,
        ]);

        $previousStatus = $this->domain->last_status;

        $this->domain->update([
            'last_checked_at' => now(),
            'last_status' => $isSuccess,
        ]);

        if ($previousStatus !== false && !$isSuccess) {
            $this->notifyUser($check);
        }
    }

    protected function notifyUser(DomainCheck $check): void
    {
        $channel = $this->domain->notify_channel->value;

        if ($channel === 'none') {
            return;
        }

        $this->domain->user->notify(new DomainDownNotification($this->domain, $check));
    }
}
