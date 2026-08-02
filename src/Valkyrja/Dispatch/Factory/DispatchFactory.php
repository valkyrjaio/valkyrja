<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Dispatch\Factory;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;
use Valkyrja\Dispatch\Data\CallableDispatch;
use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Dispatch\Data\ConstantDispatch;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Dispatch\Data\PropertyDispatch;
use Valkyrja\Dispatch\Throwable\Exception\DispatchInvalidReflectionFunctionException;

use function is_callable;

class DispatchFactory
{
    public static function fromReflection(
        ReflectionClassConstant|ReflectionProperty|ReflectionMethod|ReflectionClass|ReflectionFunction $reflection
    ): ConstantDispatch|PropertyDispatch|MethodDispatch|ClassDispatch|CallableDispatch {
        /** @var non-empty-string $name */
        $name = $reflection->getName();

        return match (true) {
            $reflection instanceof ReflectionClassConstant => new ConstantDispatch(
                constant: $name,
                class: $reflection->getDeclaringClass()->getName(),
            ),
            $reflection instanceof ReflectionProperty      => new PropertyDispatch(
                class: $reflection->getDeclaringClass()->getName(),
                property: $name,
                isStatic: $reflection->isStatic()
            ),
            $reflection instanceof ReflectionMethod        => new MethodDispatch(
                class: $reflection->getDeclaringClass()->getName(),
                method: $name,
                isStatic: $reflection->isStatic()
            ),
            $reflection instanceof ReflectionClass         => new ClassDispatch(
                class: $reflection->getName(),
            ),
            // The parameter's union type makes ReflectionFunction the only
            // remaining possibility, so this is `default` rather than a fifth
            // `instanceof` arm -- an exhaustive match compiles in an
            // UnhandledMatchError throw that no input can reach.
            default                                        => new CallableDispatch(
                callable: is_callable($name)
                    ? $name
                    : throw new DispatchInvalidReflectionFunctionException('ReflectionFunction has no valid callable'),
            ),
        };
    }
}
