<?php

declare(strict_types=1);

namespace DPanta\PHPStan\Containers\Tests\Data;

use function PHPStan\Testing\assertType;

/**
 * @param PrefixedContainerInterface $container
 */
function testConfigurableContainerGetWithClassString(PrefixedContainerInterface $container): void
{
	$service = $container->get(\stdClass::class);
	assertType('stdClass', $service);
}

/**
 * @param PrefixedContainerInterface $container
 */
function testConfigurableContainerGetWithDateTime(PrefixedContainerInterface $container): void
{
	$service = $container->get(\DateTime::class);
	assertType('DateTime', $service);
}

/**
 * @param PrefixedContainerInterface $container
 */
function testConfigurableContainerGetWithStringId(PrefixedContainerInterface $container): void
{
	// Non-class strings fall back to the default mixed return type.
	$service = $container->get('some.service.id');
	assertType('mixed', $service);
}

/**
 * @param PrefixedContainerInterface $container
 * @param string $dynamicId
 */
function testConfigurableContainerGetWithDynamicId(PrefixedContainerInterface $container, string $dynamicId): void
{
	// Dynamic strings cannot be resolved to a type.
	$service = $container->get($dynamicId);
	assertType('mixed', $service);
}
