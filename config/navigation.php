<?php
return [
    [
        'text' => 'Dashboard',
        'route' => 'dashboard',
    ],
    [
        'text' => 'Loans',
        'route' => '#'
    ],
    [
        'text' => 'Books',
        'route' => '#',
    ],
    [
        'text' => 'Users',
        'route' => '#',
        'children' => [
            [
                'text' => 'All Users',
                'route' => '#',
            ],
            [
                'text' => 'Add New User',
                'route' => '#',
            ],
            [
                'text' => 'Invitation Codes',
                'route' => '#',
            ],
        ],
    ],
];
