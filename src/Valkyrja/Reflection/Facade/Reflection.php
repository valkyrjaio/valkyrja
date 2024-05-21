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

namespace Valkyrja\Reflection\Facade;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Valkyrja\Facade\ContainerFacade;
use Valkyrja\Reflection\Contract\Reflection as Contract;

/**
 * Class Reflection.
 *
 * @author Melech Mizrachi
 *
 * @method static ReflectionClass    forClass(string $class)
 * @method static ReflectionProperty forClassProperty(string $class, string $property)
 * @method static ReflectionMethod   forClassMethod(string $class, string $method)
 * @method static ReflectionFunction forFunction(string $function)
 * @method static ReflectionFunction forClosure(Closure $closure)
 * @method static string[]           getDependencies(ReflectionFunctionAbstract $reflection)
 * @method static string[]           getDependenciesFromParameters(ReflectionParameter ...$parameters)
 */
class Reflection extends ContainerFacade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object
    {
        return self::getContainer()->getSingleton(Contract::class);
    }
}
