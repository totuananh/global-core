<?php

return [
    'host' => env('CLICKHOUSE_HOST', 'http://172.16.104.160'),
    'port' => env('CLICKHOUSE_PORT', 8123),
    'username' => env('CLICKHOUSE_USERNAME', 'default'),
    'password' => env('CLICKHOUSE_PASSWORD', ''),
];