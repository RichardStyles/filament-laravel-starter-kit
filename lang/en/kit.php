<?php

declare(strict_types=1);

return [
    'welcome' => [
        'eyebrow' => 'Laravel starter kit',
        'headline' => 'Ship your next Laravel app in an afternoon.',
        'subheadline' => 'A batteries-included starter with authentication, two-factor, role-based authorization, profile management, and an admin panel — all wired together with Livewire 4, Filament 5, and Tailwind 4.',
        'cta_register' => 'Create your account',
        'cta_sign_in' => 'Sign in',
        'cta_dashboard' => 'Go to dashboard',
        'features' => [
            'auth' => [
                'title' => 'Authentication out of the box',
                'body' => 'Login, registration, password reset, email verification, and two-factor TOTP — all wired through Fortify with Livewire forms.',
            ],
            'roles' => [
                'title' => 'Roles and policies',
                'body' => 'Spatie permissions seeded with admin and user roles. A worked UserPolicy demonstrates the pattern.',
            ],
            'tooling' => [
                'title' => 'Quality tooling',
                'body' => 'Pint, Larastan, Rector, Pest 4, and a GitHub Actions matrix all configured. composer check runs the full pipeline.',
            ],
        ],
        'footer_credit' => 'Built with Laravel',
    ],

    'dashboard' => [
        'heading' => 'Dashboard',
        'welcome_back' => 'Welcome back, :name.',
        'stats' => [
            'account_age' => 'Account age',
            'active_sessions' => 'Active sessions',
            'unread_notifications' => 'Unread notifications',
        ],
    ],
];
