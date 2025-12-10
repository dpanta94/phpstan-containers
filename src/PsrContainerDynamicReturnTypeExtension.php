<?php
/**
 * PHPStan extension for PSR-11 Containers.
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
use Psr\Container\ContainerInterface;

/**
 * PHPStan extension that teaches PHPStan that PSR-11 Container::get(Foo::class) returns Foo.
 */
class PsrContainerDynamicReturnTypeExtension implements DynamicMethodReturnTypeExtension {

	private ReflectionProvider $reflectionProvider;

	public function __construct( ReflectionProvider $reflectionProvider ) {
		$this->reflectionProvider = $reflectionProvider;
	}

	/**
	 * Get the class this extension applies to.
	 *
	 * @return class-string
	 */
	public function getClass(): string {
		return ContainerInterface::class;
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
