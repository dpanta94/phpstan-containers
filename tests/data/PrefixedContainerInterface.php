<?php

declare(strict_types=1);

namespace DPanta\PHPStan\Containers\Tests\Data;

/**
 * A container interface standing in for a namespace-prefixed (e.g. Strauss)
 * copy that shares no ancestor with the bundled StellarWP/PSR interfaces.
 */
interface PrefixedContainerInterface
{
	/**
	 * @param string $id
	 * @return mixed
	 */
	public function get(string $id);
}
