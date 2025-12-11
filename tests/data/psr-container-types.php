<?php

declare(strict_types=1);

namespace DPanta\PHPStan\Containers\Tests\Data;

use Psr\Container\ContainerInterface;
use function PHPStan\Testing\assertType;

/**
 * @param ContainerInterface $container
 */
function testPsrContainerGetWithClassString(ContainerInterface $container): void
{
	$service = $container->get(\stdClass::class);
	assertType('stdClass', $service);
}

/**
 * @param ContainerInterface $container
 */
function testPsrContainerGetWithDateTimeInterface(ContainerInterface $container): void
{
	$service = $container->get(\DateTimeInterface::class);
	assertType('DateTimeInterface', $service);
}

/**
 * @param ContainerInterface $container
 */
function testPsrContainerGetWithDateTime(ContainerInterface $container): void
{
	$service = $container->get(\DateTime::class);
	assertType('DateTime', $service);
}

/**
 * @param ContainerInterface $container
 */
function testPsrContainerGetWithStringId(ContainerInterface $container): void
{
	// When using a non-class string, the extension should return null and fall back to default behavior
	$service = $container->get('some.service.id');
	assertType('mixed', $service);
}

/**
 * @param ContainerInterface $container
 * @param string $dynamicId
 */
function testPsrContainerGetWithDynamicId(ContainerInterface $container, string $dynamicId): void
{
	// When using a dynamic string, the extension cannot determine the type
	$service = $container->get($dynamicId);
	assertType('mixed', $service);
}
