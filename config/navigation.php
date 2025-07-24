<?php
return [
    [
        'text' => 'Dashboard',
        'route' => 'dashboard',
        'role' => ''
    ],
    [
        'text' => 'Loans',
        'route' => 'loans.index',
        'role' => ''
    ],
    [
        'text' => 'Books',
        'route' => 'books.index',
        'role' => ''
    ],
    [
        'text' => 'Notes',
        'route' => 'notes.index',
        'role' => ''
    ],
    [
        'text' => 'Users',
        'route' => '#',
        'role' => 'admin',
        'children' => [
            [
                'text' => 'All Users',
                'route' => 'users.index',
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
