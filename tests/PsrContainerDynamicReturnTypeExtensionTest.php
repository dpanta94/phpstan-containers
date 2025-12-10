<?php

declare(strict_types=1);

namespace DPanta\PHPStan\Containers\Tests;

use PHPStan\Testing\TypeInferenceTestCase;

class PsrContainerDynamicReturnTypeExtensionTest extends TypeInferenceTestCase
{
	/**
	 * @return iterable<mixed>
	 */
	public static function dataFileAsserts(): iterable
	{
		yield from self::gatherAssertTypes(__DIR__ . '/data/psr-container-types.php');
	}

	/**
	 * @dataProvider dataFileAsserts
	 * @param mixed ...$args
	 */
	public function testFileAsserts(
		string $assertType,
		string $file,
		...$args
	): void {
		$this->assertFileAsserts($assertType, $file, ...$args);
	}

	/**
	 * @return string[]
	 */
	public static function getAdditionalConfigFiles(): array
	{
		return [__DIR__ . '/phpstan-test.neon'];
	}
}
