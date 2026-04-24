# Laravel 13 Starter Kit

A Laravel 13 starter kit with authentication, two-factor, role-based authorization, profile management, an admin panel, OAuth social sign-in, and a token-authenticated API — wired together with Livewire 4, Filament 5, Tailwind 4, and Pest 4.

## What's included

| Area | What you get |
| --- | --- |
| Authentication | Login, registration, password reset, email verification, two-factor (TOTP), browser-session management, account deletion — all Livewire 4 components driven by Filament Schemas and Fortify |
| Social sign-in | Socialite-backed GitHub + Google buttons on `/login` and `/register`, with a connected-accounts settings section |
| Authorization | spatie/laravel-permission with seeded `admin` and `user` roles, an example `UserPolicy`, and a `before()` admin-bypass pattern |
| Admin panel | Filament 5 panel at `/admin`, restricted to the `admin` role, with a `UserResource` for CRUD + role assignment |
| API | Sanctum-protected `/api/v1/*` (with `/v1/user` example) plus a settings section to issue and revoke personal-access tokens |
| Settings hub | Single `/settings` page with eight sections: profile + avatar upload, password, 2FA, appearance, API tokens, connected accounts, browser sessions, account deletion |
| Theming | Light / dark / system appearance toggle persisted per user, with a pre-paint script in the layout to prevent FOUC |
| Notifications | Filament's database notifications drawer wired through the navigation bell |
| Mail | Laravel's mail templates published and recolored to the kit's indigo palette |
| Tooling | Pint, Larastan (level 5), Rector (PHP 8.3 + quality sets), Pest 4 (with Playwright browser tests), GitHub Actions matrix on PHP 8.3/8.4 |
| Quality | `composer check` runs lint, static analysis, refactor dry-run, and the full test suite as one command |

## Requirements

- PHP 8.3+
- Composer 2
- Node 20+ and npm
- A PostgreSQL or MySQL database
- A Redis instance (optional — defaults to database queue and cache)

## Install

```bash
composer create-project richardstyles/filament-laravel-starter-kit my-app
cd my-app
composer setup
```

`composer setup` copies `.env.example` to `.env`, generates an app key, runs migrations, installs npm packages, and builds frontend assets.

If you cloned directly:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
```

The seeder creates a test admin (`test@example.com` / `password`) and five regular users.

## Running locally

```bash
composer dev
```

This launches `php artisan serve`, the queue worker, the log tailer, and the Vite dev server concurrently.

## Quality commands

```bash
composer lint        # apply Pint formatting
composer lint:test   # check Pint without applying changes (CI-safe)
composer analyse     # run Larastan
composer refactor    # apply Rector transforms
composer refactor:dry # preview Rector changes
composer test        # run the Pest suite
composer check       # all of the above as one command
```

## OAuth setup

Drop the credentials in `.env`:

```bash
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"
```

Toggle which providers appear via `SOCIALITE_PROVIDERS` (comma-separated). Add new providers by extending `config/services.php` and the `SocialController` whitelist.

## Customizing

| Want to... | Edit |
| --- | --- |
| Change the brand name | `APP_NAME` in `.env`, the logo `<img>` in `resources/views/livewire/navigation.blade.php` and `resources/views/welcome.blade.php` |
| Change the brand color | `Color::Indigo` in `app/Providers/Filament/AdminPanelProvider.php`, indigo classes in views, `#4f46e5` in `resources/views/vendor/mail/html/themes/default.css` |
| Add a navigation link | The `$links` array at the top of `resources/views/livewire/navigation.blade.php` |
| Add a settings section | Create a Livewire component, add `@livewire(...)` to `resources/views/livewire/settings/index.blade.php` |
| Add a Filament resource | `php artisan make:filament-resource Foo --generate` |
| Disable email verification | `FORTIFY_EMAIL_VERIFICATION=false` in `.env` |
| Add a locale | Drop `lang/{locale}/*.php` files mirroring `lang/en/`, set `APP_LOCALE` |

## License

Released under the MIT license. See [LICENSE](LICENSE).
