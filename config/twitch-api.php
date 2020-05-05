<?php

return [
    'client_id' => env('TWITCH_KEY', ''),
    'client_secret' => env('TWITCH_SECRET', ''),
    'redirect_url' => env('TWITCH_REDIRECT_URI', ''),
    'scopes' => [
        'viewing_activity_read',
        'user_subscriptions',
        'user_read',
        'user:read:email',
        'user:read:broadcast',
        'channel_read'
    ],
];
