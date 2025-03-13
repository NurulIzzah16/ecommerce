<?php

return [

    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-LmhJ2Q_IiVr6kAoA57smFZEj'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-rrcaDDt4hz0S1QUD'),
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'is_sanitized' => true,
    'is_3ds' => true,

];
