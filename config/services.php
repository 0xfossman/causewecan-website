<?php

return [
    'discord' => [
        'client_id' => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect' => env('DISCORD_REDIRECT_URI'),
        'bot_api_key' => env('DISCORD_BOT_API_KEY'),
        'downloads_channel_id' => env('DISCORD_DOWNLOADS_CHANNEL_ID'),
    ],

    'stormforge' => [
        'api_base_url' => env('STORMFORGE_API_BASE_URL'),
        'armory_base_url' => env('STORMFORGE_ARMORY_BASE_URL'),
        'api_key' => env('STORMFORGE_API_KEY'),
        'guild_name' => env('STORMFORGE_GUILD_NAME'),
        'realm' => env('STORMFORGE_REALM'),
    ],
];
