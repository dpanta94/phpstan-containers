# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a PHPStan extension (`dpanta94/phpstan-containers`) that provides dynamic return type resolution for dependency injection containers. When code calls `$container->get(Foo::class)`, PHPStan will understand that the return type is `Foo`.

Supports two container interfaces:
- `StellarWP\ContainerContract\ContainerInterface`
- `Psr\Container\ContainerInterface` (PSR-11)

## Commands

```bash
# Run tests
composer test

# Run PHPStan analysis
composer test:phpstan

# Install dependencies
composer install

# Run PHP syntax linting
vendor/bin/parallel-lint src/
```

## Architecture

- **`src/StellarWpContainerDynamicReturnTypeExtension.php`**: PHPStan extension for StellarWP container contract. Intercepts `get()` method calls on `StellarWP\ContainerContract\ContainerInterface` and resolves the return type based on the class name string argument.

- **`src/PsrContainerDynamicReturnTypeExtension.php`**: PHPStan extension for PSR-11 container contract. Intercepts `get()` method calls on `Psr\Container\ContainerInterface` and resolves the return type based on the class name string argument.

- **`extension.neon`**: PHPStan configuration file that registers both extensions.

- **`composer.json`**: Defines the package as type `phpstan-extension` with auto-registration via the `extra.phpstan.includes` config.

## Testing

Tests use PHPStan's `TypeInferenceTestCase` to verify type resolution:
- **`tests/StellarWpContainerDynamicReturnTypeExtensionTest.php`**: Tests for StellarWP extension
- **`tests/PsrContainerDynamicReturnTypeExtensionTest.php`**: Tests for PSR-11 extension
- **`tests/data/`**: Test fixture files containing `assertType()` calls

## Key Details

- Namespace: `DPanta\PHPStan\Containers`
- PSR-4 autoloading from `src/`
- Requires PHP 7.4+ and PHPStan 2.0+
- Both extensions implement `DynamicMethodReturnTypeExtension` interface from PHPStan
- Uses `ReflectionProvider` to check class existence (not PHP's native `class_exists`)
