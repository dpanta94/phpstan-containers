# PHPStan Container Extensions

[![PHPStan](https://img.shields.io/badge/PHPStan-enabled-239922)](https://github.com/phpstan/phpstan)

PHPStan extensions for dependency injection containers. Provides dynamic return type resolution so PHPStan understands that `$container->get(Foo::class)` returns `Foo`.

## Supported Containers

- [StellarWP Container Contract](https://github.com/stellarwp/container-contract) (`StellarWP\ContainerContract\ContainerInterface`)
- [PSR-11 Container](https://www.php-fig.org/psr/psr-11/) (`Psr\Container\ContainerInterface`)
- Any container really as long as you pass its fully qualified class name to the extension as an argument.

## Requirements

- PHP 7.4 or higher
- PHPStan 2.0 or higher

## Installation

```bash
composer require --dev dpanta94/phpstan-containers
```

If you use [phpstan/extension-installer](https://github.com/phpstan/extension-installer), you're all set!

### Manual Installation

If you don't use the extension installer, add the extension to your `phpstan.neon`:

```neon
includes:
    - vendor/dpanta94/phpstan-containers/extension.neon
```

## Usage

Once installed, PHPStan will automatically understand container return types:

```php
use Psr\Container\ContainerInterface;

class MyService {
    public function __construct(private ContainerInterface $container) {}

    public function doSomething(): void {
        // PHPStan knows $logger is an instance of Logger
        $logger = $this->container->get(Logger::class);
        $logger->info('Hello world');

        // PHPStan knows $mailer is an instance of MailerInterface
        $mailer = $this->container->get(MailerInterface::class);
        $mailer->send($message);
    }
}
```

The extension resolves types when:

- The argument to `get()` is a class-string constant (e.g., `Foo::class`)
- The class or interface exists in the codebase

When using string service IDs (e.g., `$container->get('mailer')`), the extension falls back to the default `mixed` return type.

## Prefixed containers (Strauss / Mozart)

PHPStan keys dynamic return type extensions by the class returned from `getClass()` and only consults them when the value's type has that class in its ancestry. If you ship your dependencies through a namespace prefixer such as [Strauss](https://github.com/BrianHenryIE/strauss) or Mozart, your container interface is rewritten (e.g. `StellarWP\ContainerContract\ContainerInterface` becomes `Acme\Vendor\StellarWP\ContainerContract\ContainerInterface`). The bundled StellarWP and PSR extensions can no longer match it, because the prefixed copy shares no ancestor with the original interface.

Register `ConfigurableContainerDynamicReturnTypeExtension` once per prefixed container interface in your `phpstan.neon`:

```neon
services:
    -
        class: DPanta\PHPStan\Containers\ConfigurableContainerDynamicReturnTypeExtension
        arguments:
            containerClass: Acme\Vendor\StellarWP\ContainerContract\ContainerInterface
        tags:
            - phpstan.broker.dynamicMethodReturnTypeExtension
```

The bundled defaults remain registered, so unprefixed containers keep working with zero configuration.

## License

MIT

## Credits

This package is inspired by [Phil Nelson](https://github.com/phil-nelson)'s [phpstan-container-extension](https://packagist.org/packages/phil-nelson/phpstan-container-extension).
