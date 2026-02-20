<?php

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class DiscordDownloadsService
{
    public function getDownloadableFiles(): array
    {
        $channelId = (string) config('services.discord.downloads_channel_id');
        $botApiKey = (string) config('services.discord.bot_api_key');

        if ($channelId === '' || $botApiKey === '') {
            return [];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bot '.$botApiKey,
        ])->acceptJson()->get("https://discord.com/api/v10/channels/{$channelId}/messages", [
            'limit' => 100,
        ]);

        if (! $response->successful()) {
            return [];
        }

        $downloads = [];

        foreach ($response->json() as $message) {
            foreach (Arr::get($message, 'attachments', []) as $attachment) {
                $downloads[] = [
                    'filename' => Arr::get($attachment, 'filename', 'file'),
                    'url' => Arr::get($attachment, 'url'),
                    'size' => (int) Arr::get($attachment, 'size', 0),
                    'content_type' => Arr::get($attachment, 'content_type', 'application/octet-stream'),
                    'uploaded_at' => Arr::get($message, 'timestamp'),
                ];
            }
        }

        return $downloads;
    }
}
