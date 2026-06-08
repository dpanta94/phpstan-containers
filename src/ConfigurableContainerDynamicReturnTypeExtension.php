<?php

/**
 * Configurable PHPStan extension for Containers.
 *
 * @package DPanta\PHPStan\Containers
 */

declare(strict_types=1);

namespace DPanta\PHPStan\Containers;

/**
 * PHPStan extension that resolves Container::get(Foo::class) to Foo for a
 * container class supplied through configuration.
 *
 * PHPStan keys dynamic return type extensions by the class returned from
 * getClass() and only consults them when the called value's type has that
 * class in its ancestry. The bundled StellarWP and PSR extensions therefore
 * cannot match a container whose namespace has been rewritten, for example by
 * a Strauss/Mozart prefixer that turns
 * StellarWP\ContainerContract\ContainerInterface into
 * Acme\Vendor\StellarWP\ContainerContract\ContainerInterface.
 *
 * Register this extension once per prefixed container interface in your
 * phpstan.neon:
 *
 * services:
 *     -
 *         class: DPanta\PHPStan\Containers\ConfigurableContainerDynamicReturnTypeExtension
 *         arguments:
 *             containerClass: Acme\Vendor\StellarWP\ContainerContract\ContainerInterface
 *         tags:
 *             - phpstan.broker.dynamicMethodReturnTypeExtension
 */
final class ConfigurableContainerDynamicReturnTypeExtension extends AbstractContainerDynamicReturnTypeExtension
{
    /**
     * @var class-string
     */
    private string $containerClass;

    /**
     * @param class-string $containerClass The container interface or class this extension applies to.
     */
    public function __construct(string $containerClass)
    {
        $this->containerClass = $containerClass;
    }

    /**
     * Get the class this extension applies to.
     *
     * @return class-string
     */
    public function getClass(): string
    {
        return $this->containerClass;
    }
}
