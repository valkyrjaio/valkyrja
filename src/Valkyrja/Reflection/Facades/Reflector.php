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

namespace Valkyrja\Reflection\Facades;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Reflector.
 *
 * @author Melech Mizrachi
 *
 * @method static ReflectionClass getClassReflection(string $class)
 * @method static ReflectionProperty getPropertyReflection(string $class, string $property)
 * @method static ReflectionMethod getMethodReflection(string $class, string $method)
 * @method static ReflectionFunction getFunctionReflection(string $function)
 * @method static ReflectionFunction getClosureReflection(Closure $closure)
 * @method static string[] getDependencies(ReflectionFunctionAbstract $reflection)
 * @method static string[] getDependenciesFromParameters(ReflectionParameter ...$parameters)
 */
class Reflector extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return \Valkyrja\reflector();
    }
}
