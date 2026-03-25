<?php

namespace App\Notifications;

use App\Enums\NotifyChannel;
use App\Models\Domain;
use App\Models\DomainCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class DomainDownNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Domain $domain,
        public DomainCheck $check
    ) {}

    public function via(object $notifiable): array
    {
        $channel = $this->domain->notify_channel;

        return match ($channel) {
            NotifyChannel::Email => ['mail'],
            NotifyChannel::Telegram => ['telegram'],
            NotifyChannel::Both => ['mail', 'telegram'],
            default => [],
        };
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $this->domain->name ?: $this->domain->url;

        return (new MailMessage)
            ->error()
            ->subject("Domain Down: {$name}")
            ->greeting('Domain Monitor Alert')
            ->line("Domain **{$name}** is down!")
            ->line("URL: {$this->domain->url}")
            ->line("Status Code: " . ($this->check->status_code ?: 'N/A'))
            ->line("Error: " . ($this->check->error ?: 'No response'))
            ->action('View Domain', url("/domains/{$this->domain->id}"))
            ->line('Please check your server.');
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $name = $this->domain->name ?: $this->domain->url;
        $status = $this->check->status_code ?: 'N/A';
        $error = $this->check->error ?: 'No response';

        $text = "🔴 Domain Down: {$name}\n\n"
            . "URL: {$this->domain->url}\n"
            . "Status: {$status}\n"
            . "Error: {$error}";

        return TelegramMessage::create()
            ->to($notifiable->telegram_id)
            ->content($text)
            ->options(['parse_mode' => '']);
    }
}
