<?php

/**
 * PHPStan extension for PSR-11 Containers.
 *
 * @package DPanta\PHPStan\Containers
 */

declare(strict_types=1);

namespace DPanta\PHPStan\Containers;

use Psr\Container\ContainerInterface;

/**
 * PHPStan extension that teaches PHPStan that PSR-11 Container::get(Foo::class) returns Foo.
 */
class PsrContainerDynamicReturnTypeExtension extends AbstractContainerDynamicReturnTypeExtension
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
