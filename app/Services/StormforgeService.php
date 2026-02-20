<?php

namespace App\Services;

use App\Models\GuildMember;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class StormforgeService
{
    public function getGuildRoster(): array
    {
        $apiBase = rtrim((string) config('services.stormforge.api_base_url'), '/');
        $armoryBase = rtrim((string) config('services.stormforge.armory_base_url'), '/');

        if ($apiBase === '') {
            return GuildMember::query()->orderBy('name')->get()->toArray();
        }

        $response = Http::withToken((string) config('services.stormforge.api_key'))
            ->acceptJson()
            ->get($apiBase.'/guild/roster', [
                'guild' => config('services.stormforge.guild_name'),
                'realm' => config('services.stormforge.realm'),
            ]);

        if (! $response->successful()) {
            return GuildMember::query()->orderBy('name')->get()->toArray();
        }

        $members = Arr::get($response->json(), 'members', []);

        foreach ($members as $member) {
            GuildMember::query()->updateOrCreate(
                ['name' => Arr::get($member, 'name')],
                [
                    'class_name' => Arr::get($member, 'class'),
                    'spec_name' => Arr::get($member, 'spec'),
                    'level' => Arr::get($member, 'level', 0),
                    'race' => Arr::get($member, 'race'),
                    'rank_name' => Arr::get($member, 'rank'),
                    'avatar_url' => Arr::get($member, 'avatar'),
                    'armory_url' => Arr::get($member, 'url', $armoryBase),
                    'source_updated_at' => now(),
                ]
            );
        }

        return GuildMember::query()->orderByDesc('level')->orderBy('name')->get()->toArray();
    }
}
