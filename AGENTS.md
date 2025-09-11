# AGENTS.md — Guidance for Codex

These instructions apply to the entire repository.

## Overview
- Project type: Laravel package (PHP 8.2), autoloaded via PSR-4 under `Mkb\KatmSdkLaravel\`.
- Testing: PHPUnit with Orchestra Testbench. Feature tests marked `@group live` call real external APIs.
- Entry points: Service Provider `Mkb\KatmSdkLaravel\Providers\KatmSdkServiceProvider`, Facade `Mkb\KatmSdkLaravel\Facades\Katm`.

## Setup
- Install dev deps and dump autoload:
  - `make dump-autoload-install` (installs `orchestra/testbench` and optimizes autoload)
- Regenerate autoload after changes:
  - `make dump-autoload`

## Running Tests
- Safe default (no real API):
  - `./vendor/bin/phpunit` (be aware some Unit tests may still reach network; see “Network & Live tests”).
- Live API Feature tests only:
  - `make unit-test` (runs `--group live`). Requires valid credentials and network.

## Network & Live tests
- Do not introduce network calls in tests unless they are explicitly tagged with `@group live` and guarded by environment checks.
- Prefer mocking or faking HTTP for Unit tests. Use real HTTP only in Feature tests grouped as `live`.
- If adding a live test, skip when credentials are missing:
  - Example pattern: check required env/config and call `$this->markTestSkipped('reason')` when not present.

## Secrets & Credentials
- Never commit real secrets. Current `tests/TestCase.php` contains hardcoded values used for manual/live testing; do not replicate this pattern. When editing, consider moving such values behind env variables for safety.
- Do not print secrets or tokens in logs or exceptions.

## Coding Style & Conventions
- Follow PSR-4 namespaces and keep files in `src/` mirroring `Mkb\KatmSdkLaravel\...`.
- Follow PSR-12 coding style as a guideline (match existing style in this repo; do not add headers like `declare(strict_types=1)` unless consistently applied project-wide).
- Use explicit return types and parameter types where practical.
- Keep surface area small: avoid introducing breaking changes to public API (`Katm` facade methods, manager/service signatures) without discussion.
- Keep exceptions meaningful; prefer custom exceptions in `src/Exceptions/` when adding new error types.

## Package Structure
- `src/Providers/KatmSdkServiceProvider.php`: Binds services and publishes config. If adding services, bind singletons using clear container keys.
- `src/Facades/Katm.php`: Exposes the public API. Update the facade docblocks when adding new facade methods.
- `config/katm.php`: Configuration defaults. Add new options here and reference via `config('katm.key')`.
- `src/Services/*`: HTTP client logic. Keep request/response shaping and retry logic here.
- `src/Responses/*`: Data DTOs using `spatie/laravel-data`. Add parsing helpers here rather than scattering array access.
- `src/Enums/*`: Centralize required fields and constants.

## HTTP & Retries
- Reuse the common client logic in `AbstractHttpClientService` (headers, retries, timeout, proxy).
- Do not duplicate base URL, headers, or retry setup in child services; extend and configure via protected helpers.
- Preserve existing behavior for token caching (`KatmAuthService`) when modifying auth flows.

## Adding Dependencies
- Add runtime deps to `require` and dev/test tools to `require-dev` in `composer.json`.
- Keep the dependency set minimal and compatible with Laravel 10–12.
- After changes, run `make dump-autoload`.

## Documentation
- Update `README.md` if you change setup, configuration keys, or the public API.
- If you add config options, document their env equivalents.

## Versioning
- This is a library package. If you introduce breaking changes, bump the version in `composer.json` and note them in the README (or a CHANGELOG if one is added later).

## Don’ts
- Don’t commit unrelated refactors with a feature or fix.
- Don’t add license or copyright headers.
- Don’t run or require long-running external services in unit tests.

## Quick Reference
- Autoload optimize: `make dump-autoload`
- Install dev tools: `make dump-autoload-install`
- Run PHPUnit: `./vendor/bin/phpunit`
- Live Feature tests: `make unit-test` (requires network + credentials)

