<?php
return [
    [
        'text' => 'Dashboard',
        'route' => 'dashboard',
    ],
    [
        'text' => 'Loans',
        'route' => 'loans.index'
    ],
    [
        'text' => 'Books',
        'route' => 'books.index',
    ],
    [
        'text' => 'Users',
        'route' => '#',
        'children' => [
            [
                'text' => 'All Users',
                'route' => 'user.index',
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
