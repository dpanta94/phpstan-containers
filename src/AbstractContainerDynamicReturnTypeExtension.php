<?php
/**
 * Abstract PHPStan extension for Container type resolution.
 *
 * @package DPanta\PHPStan\Containers
 */

declare(strict_types=1);

namespace DPanta\PHPStan\Containers;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

/**
 * Abstract base class for container dynamic return type extensions.
 *
 * This class provides shared logic for resolving Container::get(Foo::class) to return type Foo.
 * Subclasses only need to implement getClass() to specify which container interface they support.
 */
abstract class AbstractContainerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension {

	private ReflectionProvider $reflectionProvider;

	public function __construct( ReflectionProvider $reflectionProvider ) {
		$this->reflectionProvider = $reflectionProvider;
	}

	/**
	 * Check if this extension handles the given method.
	 *
	 * @param MethodReflection $methodReflection The method reflection.
	 *
	 * @return bool
	 */
	public function isMethodSupported( MethodReflection $methodReflection ): bool {
		return $methodReflection->getName() === 'get';
	}

	/**
	 * Get the return type for the method call.
	 *
	 * If the first argument is a class-string constant (e.g., Foo::class),
	 * this returns an ObjectType for that class. Otherwise, returns null
	 * to fall back to PHPStan's default behavior.
	 *
	 * @param MethodReflection $methodReflection The method reflection.
	 * @param MethodCall       $methodCall       The method call node.
	 * @param Scope            $scope            The analysis scope.
	 *
	 * @return Type|null
	 */
	public function getTypeFromMethodCall(
		MethodReflection $methodReflection,
		MethodCall $methodCall,
		Scope $scope
	): ?Type {
		if ( count( $methodCall->getArgs() ) === 0 ) {
			return null;
		}

		$arg  = $methodCall->getArgs()[0]->value;
		$type = $scope->getType( $arg );

		$classNames = $type->getConstantStrings();
		if ( count( $classNames ) === 1 ) {
			$className = $classNames[0]->getValue();
			if ( $this->reflectionProvider->hasClass( $className ) ) {
				return new ObjectType( $className );
			}
		}

		return null;
	}
}
