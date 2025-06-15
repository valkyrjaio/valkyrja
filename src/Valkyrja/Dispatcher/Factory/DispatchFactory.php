<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Dispatcher\Factory;

use ReflectionClass;
use ReflectionClassConstant;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;
use Valkyrja\Dispatcher\Data\CallableDispatch;
use Valkyrja\Dispatcher\Data\ClassDispatch;
use Valkyrja\Dispatcher\Data\ConstantDispatch;
use Valkyrja\Dispatcher\Data\MethodDispatch;
use Valkyrja\Dispatcher\Data\PropertyDispatch;
use Valkyrja\Dispatcher\Exception\RuntimeException;

use function is_callable;

/**
 * Class DispatcherFactory.
 *
 * @author Melech Mizrachi
 */
class DispatchFactory
{
    public static function fromReflection(
        ReflectionClassConstant|ReflectionProperty|ReflectionMethod|ReflectionClass|ReflectionFunction $reflection
    ): ConstantDispatch|PropertyDispatch|MethodDispatch|ClassDispatch|CallableDispatch {
        return match (true) {
            $reflection instanceof ReflectionClassConstant => new ConstantDispatch(
                constant: $reflection->getName(),
                class: $reflection->getDeclaringClass()->getName(),
            ),
            $reflection instanceof ReflectionProperty => new PropertyDispatch(
                class: $reflection->getDeclaringClass()->getName(),
                property: $reflection->getName(),
                isStatic: $reflection->isStatic()
            ),
            $reflection instanceof ReflectionMethod => new MethodDispatch(
                class: $reflection->getDeclaringClass()->getName(),
                method: $reflection->getName(),
                isStatic: $reflection->isStatic()
            ),
            $reflection instanceof ReflectionClass => new ClassDispatch(
                class: $reflection->getName(),
            ),
            $reflection instanceof ReflectionFunction => new CallableDispatch(
                callable: is_callable($functionName = $reflection->getName())
                    ? $functionName
                    : throw new RuntimeException('ReflectionFunction has no valid callable'),
            ),
        };
    }
}
