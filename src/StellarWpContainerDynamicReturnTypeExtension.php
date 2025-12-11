<?php

/**
 * PHPStan extension for StellarWP Containers.
 *
 * @package DPanta\PHPStan\Containers
 */

declare(strict_types=1);

namespace DPanta\PHPStan\Containers;

use StellarWP\ContainerContract\ContainerInterface;

/**
 * PHPStan extension that teaches PHPStan that StellarWP Container::get(Foo::class) returns Foo.
 */
class StellarWpContainerDynamicReturnTypeExtension extends AbstractContainerDynamicReturnTypeExtension
{
    /**
     * Get the class this extension applies to.
     *
     * @return class-string
     */
    public function getClass(): string
    {
        return ContainerInterface::class;
    }
}
