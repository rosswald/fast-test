<?php

return [
    'db' => [
        'database' => 'database/database.sqlite',
        'schema' => 'database/schema.sql',
    ],
    'roles' => [
        'user' => [
            'permissions' => [
                'authenticate',
                'view-page',
            ],
        ],
        'admin' => [
            'inherit' => [
                'user',
            ],
            'permissions' => [
                'view-stats',
            ],
        ],
    ],
];