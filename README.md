# WordPress-Dev Boilerplate

[![version](https://flat.badgen.net/badge/release/v1.1.3/blue)](https://gitlab.com/merci-digital/wordpress-dev-boilerplate)
[![license](https://flat.badgen.net/badge/license/ISC/orange)](https://fr.wikipedia.org/wiki/Licence_ISC)
[![versionWP](https://flat.badgen.net/badge/wordpress/6.9.1/blue)](https://fr.wordpress.org/)
[![author](https://flat.badgen.net/badge/author/Thomas%20Fleury/green)](https://gitlab.com/thomas.fleury73)
[![co-author](https://flat.badgen.net/badge/co-author/Luc%20Moiraud/cyan)](https://gitlab.com/duariomg1)

# WordPress Modern Boilerplate

## Overview

WordPress Modern Boilerplate is an internal development workspace used to build custom WordPress themes efficiently.

It provides a modern, lightweight and maintainable toolchain built around:

* Gulp 4 (task orchestration)
* ESBuild (JavaScript bundling – ES2019 target)
* Dart Sass (`@use` / `@forward`)
* PostCSS (Autoprefixer)
* BrowserSync (WordPress proxy)
* Vanilla JavaScript (no jQuery)

---

## Stack (v1.0.0)

* Gulp 4
* ESBuild
* Dart Sass
* PostCSS
* BrowserSync
* Node >= 20
* npm >= 9.4

---

## Requirements

* Node >= 20.0.0
* npm >= 9.4.0
* Composer >= 2.0
* A local WordPress environment (MAMP)

Check versions:

```bash
node -v
npm -v
composer -v
```

---

## Installation

Create a new project using Composer:

```bash
composer create-project wearemerci/wp-boilerplate your-project-name
```

The setup script will automatically:

1. Ask for your database credentials
2. Ask for your preferred local URL type
3. Generate a `.env` file with all environment variables
4. Generate unique WordPress security salts
5. Install npm dependencies inside `wp-dev/`
6. Configure your MAMP virtual host and `/etc/hosts`

> ⚠️ The virtual host configuration requires `sudo` — you may be prompted for your password.

Once complete, restart MAMP and access:

```
http://your-project-name.merci
```

---

## MAMP Setup

MAMP configuration is handled automatically by `scripts/vhost.sh` during installation.

It will:

* Add a virtual host entry to `/Applications/MAMP/conf/apache/extra/httpd-vhosts.conf`
* Add the domain to `/etc/hosts`

If you need to configure it manually, follow these steps:

### 1. Install MAMP

[https://www.mamp.info/en/downloads/](https://www.mamp.info/en/downloads/)

### 2. Configure Ports

In **MAMP → Preferences → Ports**, set:

* Apache: `80`
* MySQL: `3306`

### 3. Enable Virtual Hosts

Edit `/Applications/MAMP/conf/apache/httpd.conf` and uncomment:

```
Include /Applications/MAMP/conf/apache/extra/httpd-vhosts.conf
```

### 4. Database Setup

1. Start MAMP
2. Go to `http://localhost/phpMyAdmin`
3. Create a new empty database
4. Use that database name when prompted during `composer create-project`

---

## Environment Variables

The `.env` file is automatically generated at the root of your project during installation.

```env
# Database configuration
WP_DB_NAME=your-project
WP_DB_USER=root
WP_DB_PASSWORD=root
WP_DB_HOST=localhost

# Theme & URL
THEME_NAME=your-project
SITE_URL=http://your-project.merci

# WordPress security salts (auto-generated)
WP_AUTH_KEY="..."
WP_SECURE_AUTH_KEY="..."
WP_LOGGED_IN_KEY="..."
WP_NONCE_KEY="..."
WP_AUTH_SALT="..."
WP_SECURE_AUTH_SALT="..."
WP_LOGGED_IN_SALT="..."
WP_NONCE_SALT="..."
```

> ⚠️ Never commit `.env` — it is listed in `.gitignore`

---

## WordPress Configuration

In `wp-config.php`, phpdotenv loads the `.env` automatically:

```php
require_once __DIR__ . '/vendor/autoload.php';

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
}

define('DB_NAME',     $_ENV['WP_DB_NAME']);
define('DB_USER',     $_ENV['WP_DB_USER']);
define('DB_PASSWORD', $_ENV['WP_DB_PASSWORD']);
define('DB_HOST',     $_ENV['WP_DB_HOST']);
```

Recommended local settings:

```php
define('WP_ENVIRONMENT_TYPE', 'local');
define('WP_DEBUG', true);
```

---

## Development

Start development mode:

```bash
npm run dev
```

This will:

* Compile Sass (Dart Sass)
* Bundle JavaScript via ESBuild
* Generate sourcemaps
* Start BrowserSync
* Watch all files

---

## Production Build

```bash
npm run build
```

This will:

* Minify CSS
* Minify JS
* Generate production-ready files

---

## Available Scripts

```bash
npm run sass        # Compile Sass only
npm run js          # Bundle JavaScript only
npm run audit:prod  # Audit production dependencies
npm run audit:all   # Audit all dependencies
```

---

## Project Structure

```
/your-project
│
├── scripts/                # Setup scripts (run once at installation)
│   ├── setup.php           # Orchestrator
│   ├── env.php             # .env generation + WordPress salts
│   ├── npm.php             # npm install in wp-dev/
│   └── vhost.sh            # MAMP virtual host configuration
│
├── wp-dev/                 # Development workspace
│   ├── gulpfile.js
│   ├── package.json
│   └── src/
│       ├── js/
│       ├── sass/
│       ├── pug/
│       └── wordpress/
│
├── wp-content/             # WordPress themes & plugins
├── .env                    # Local environment variables (not committed)
├── composer.json
└── wp-config.php
```

---

## Versioning

This project follows Semantic Versioning.

* `1.0.0` → First stable architecture
* `1.x` → Improvements without breaking changes
* `2.0.0` → Future architectural changes

See full history in `CHANGELOG.md`.

---

## Security

```bash
npm audit --omit=dev
```

Current status (v1.0.0): 0 production vulnerabilities

---

## Troubleshooting

### Node Version Issues

```bash
node -v  # Required: >= 20.0.0
npm -v   # Required: >= 9.4.0
```

Use `nvm` if needed.

### Dependency Installation Issues

```bash
rm -rf node_modules package-lock.json
npm install
```

### Build Errors (Sass / JS)

1. Verify your `.env` file exists at root
2. Verify your Node version
3. Run a production build to test: `npm run build`

### BrowserSync Not Working

* Ensure `SITE_URL` in `.env` matches your local WordPress URL
* Ensure WordPress is running
* Ensure `WP_ENVIRONMENT_TYPE` is set in `wp-config.php`

### Environment Variables Not Set

```
Database environment variables are not set.
```

1. `.env` exists at root
2. No spaces around `=`
3. `vendor/autoload.php` exists
4. Composer was executed in the correct directory