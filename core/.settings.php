<?php
return [
    'connections' => [
        'value' => [
            'default' => [
                'host' => 'MySQL-8.0',
                'database' => 'groupfive',
                'login' => 'root',
                'password' => ''
            ],
            'localhost' => [
                'host' => 'MongoDB',
                'database' => 'groupfive',
                'login' => 'root',
                'password' => ''
            ]
        ]
    ],
    'session' => [
        'value' => [
            'mode' => 'default'
        ],
        'readonly' => true
    ],
    'cookie' => [
        'value' => [
            'secure' => false,
            'http_only' => true
        ],
        'readonly' => false
    ],
    'cache_flags' => [
        'value' => [
            'config_options' => 3600,
            'site_domain' => 3600
        ],
        'readonly' => false
    ]
];
