# Changelog

## [1.1.3] — 26 February 2026

### Fixed
* vhost.sh absolute paths

## [1.1.2] — 27 February 2026

### Added
* Split setup into multiple scripts (env.php, npm.php, vhost.sh)
* Auto-generate WordPress security salts from API
* Add URL type selection (virtual host, localhost, custom)
* Add default values fallback for all inputs
* Add script existence protection in orchestrator
* Display next steps after project setup

---

## [1.1.1] — 26 February 2026

### Added
* Create folder scripts and move setup script inside
* Rename variables into setup script

---

## [1.1.0] — 26 February 2026

### Added
* Composer-based project initialization via `composer create-project`
* Interactive setup script (`setup.php`)
* Automatic `.env` generation on project creation

---

## [1.0.2] — 23 February 2026

### 🛠 Patch — Production CSS Stability Fix

### Fixed

* Production CSS rendering issue caused by aggressive PostCSS transformations
* Removed `postcss-sort-media-queries`, `postcss-combine-media-query`, and `postcss-combine-duplicated-selectors` from the production build

### Changed

* Simplified `min_css()` pipeline to use:

  * `postcss-import`
  * `autoprefixer`
  * `cssnano`
* Preserved original cascade order between development (`main.css`) and production (`main.min.css`)

### Impact

* Restores correct layout behavior for Swiper-based components in production
* Eliminates dev/prod rendering inconsistencies
* Improves build predictability and CSS safety

---

## [1.0.1] — 21 February 2026

### 🔧 Patch — Infomaniak Environment Variables Compatibility

### Added
- Support for Base64-encoded WordPress salts/keys via environment variables
- Safe decoding fallback logic in `wp-config.php`

### Changed
- WordPress keys/salts loading strategy to improve hosting compatibility

### Security
- Prevents exposing raw salts/keys in hosting panels with restricted characters

## [1.0.0] — 20 February 2026

### 🚀 Major Release — Architecture Stabilization

This release marks the first stable version of the WordPress-Dev Boilerplate.

Version 1.0.0 modernizes both the front-end toolchain and the local WordPress configuration architecture.
It introduces a secure, environment-based setup using Composer and phpdotenv while stabilizing the JavaScript and Sass build system.

---

### Added

* ESBuild as JavaScript bundler (ES2019 target)
* Modular JavaScript architecture (split modules instead of a single `main.js`)
* Sass Modules architecture using `@use` and `@forward`
* Environment configuration via `.env` file for Gulp (theme name, site URL)
* Composer integration at WordPress root
* `vlucas/phpdotenv` for local environment configuration
* Database configuration via `.env` (no hardcoded credentials)
* Automatic loading of environment variables in `wp-config.php`
* Root-level `.gitignore` protection for `.env` and `/vendor`
* Clean separation between development tooling (`wp-dev`) and WordPress core
* Production-ready build separation (dev vs prod)

---

### Changed

* Removed hardcoded database credentials from `wp-config.php`
* `wp-config.php` now loads Composer autoload
* WordPress reads database configuration from `$_ENV`
* Improved local environment isolation
* Refactored JS structure for better maintainability
* Replaced legacy JavaScript builder with ESBuild
* Migrated from deprecated `node-sass` to Dart Sass
* Simplified PostCSS configuration
* Improved build reliability and performance
* Reduced total dependencies from ~110 to ~20
* Eliminated all production vulnerabilities

---

### Removed

* jQuery dependency
* Deprecated Sass `@import` usage
* External `.min.js` plugin files
* Unused legacy build plugins
* `node-sass`
* Hardcoded database configuration in versioned files

---

### Security

* `npm audit --omit=dev` → 0 vulnerabilities
* Sensitive data removed from version control
* `.env` excluded from Git
* Composer dependencies isolated in `/vendor`
* Cleaner local configuration architecture

---

This version establishes a stable and modern foundation for future iterations of the boilerplate.

---

# Previous Versions

---

### 22 November 2024 — v0.1.9(.86)

* Updated WordPress to 6.6.2
* Sass 3.0.0 update

  * Added `@use 'sass:list'` and `@use 'sass:math'` in `white-space.sass` and `grid.sass`
* Updated grid max size (prevent oversize)
  ⚠️ Use `clamp()` instead of `max()`

---

### 27 August 2024 — v0.1.9(.85)

* Updated WordPress to 6.6.1

---

### 17 April 2024 — v0.1.9(.84)

* Updated WordPress to 6.5.2
* Updated plugins

---

### 3 January 2024 — v0.1.9(.83)

* Added `gulp export_modules_json`
* Added `gulp import_modules_json`

---

### 5 December 2023 — v0.1.9(.82)

* Grid update

---

### 24 November 2023 — v0.1.9(.81)

* Updated WordPress to 6.4.1
* Added `acf-beautiful-flexible`
* Added `acf-hide-layout`

---

### 27 September 2023 — v0.1.9(.8)

* Changed default WordPress image resizing

---

### 4 September 2023 — v0.1.9(.7)

* Updated WordPress to 6.3.1

---

### 7 April 2023 — v0.1.9(.6)

* ⚠️ Node-sass deprecated — migrated to Sass
* Updated `mixins.sass`

---

### 30 January 2023 — v0.1.9(.5)

* Updated grid 😎
* Updated `gulpfile.js`

---

### 27 January 2023 — v0.1.9(.4)

* Updated `package.json`
  ⚠️ Do not change:

  * `gulp-babel ^7.0.0 → ^8.0.0`
  * `gulp-imagemin ^7.1.0 → ^8.0.0`

---

### 16 December 2022 — v0.1.9(.3)

* Updated WordPress to 6.1.1
* Updated npm packages
* Updated `footer.pug`
* Updated `functions.php`
* Renamed branch `master` → `main`

---

### 25 October 2022 — v0.1.9(.2)

* Updated WordPress to 6.0.3
* Integrated `colors.scss`

---

### 28 June 2022 — v0.1.9(.1)

* Updated WordPress to 6.0.0

---

### 4 May 2022 — v0.1.9

* Updated README
* Updated WordPress to 5.9.3
* Updated Sass architecture:

  * Custom Bootstrap grid with `xxl` breakpoint
  * Updated `variables.sass`
  * Updated `reset.sass`
  * Updated `misc.sass`
  * Updated `button.sass`

---

### 27 April 2022 — v0.1.8

* Renamed `workspace` → `wp-dev`
* Updated README:

  * Added GitLab remote change instructions
  * Added Troubleshooting section
  * Updated changelog ordering (latest on top)
* Updated `gulpfile`

---

### 30 March 2022 — v0.1.7

* Updated workspace and README

---

### 21 May 2021 — v0.1.6

* Added `gulp dump`

---

### 27 February 2021 — v0.1.5

* Structure update
* Updated WordPress to 5.6.2

---

### 2020 — v0.1.4

* WordPress integrated into project

---

### 2 March 2019 — v0.1.3

* Migrated SCSS to Sass
* Custom Bootstrap
* Automatic `main.sass` generation
* Sass auto-import in directory
* Shift from components to modules
* Default navbar
* Added useful links

---

### 13 November 2019 — v0.1.2

---

### 2 October 2019 — v0.1.1

* Added HTTPS repository link

---

### 28 September 2019 — v0.1

* Initial SCSS/Pug structure

---