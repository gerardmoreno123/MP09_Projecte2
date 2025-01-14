<?php

return [
    'default_user' => [
        'name' => env('DEFAULT_USER_NAME', 'gmoreno'),
        'email' => env('DEFAULT_USER_EMAIL', 'gerardmoreno@iesebre.com'),
        'password' => env('DEFAULT_USER_PASSWORD', 'gmoreno'),
        'team_name' => env('DEFAULT_USER_TEAM_NAME', 'gmoreno\'s Team'),
    ],
    'default_teacher' => [
        'name' => env('DEFAULT_TEACHER_NAME', 'jvega'),
        'email' => env('DEFAULT_TEACHER_EMAIL', 'jordivega@iesebre.com'),
        'password' => env('DEFAULT_TEACHER_PASSWORD', 'jvega'),
        'team_name' => env('DEFAULT_TEACHER_TEAM_NAME', 'jvega\'s Team'),
    ],
];
