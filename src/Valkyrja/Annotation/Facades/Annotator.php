<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotation\Facades;

use ReflectionClass;
use ReflectionFunction;
use ReflectionMethod;
use ReflectionProperty;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Filter;
use Valkyrja\Annotation\Parser;
use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;

/**
 * Class Annotator.
 *
 * @author Melech Mizrachi
 *
 * @method static Filter filter()
 * @method static void setFilter(Filter $filter)
 * @method static Parser parser()
 * @method static void setParser(Parser $parser)
 * @method static Annotation[] classAnnotations(string $class)
 * @method static Annotation[] classMembersAnnotations(string $class)
 * @method static Annotation[] classAndMembersAnnotations(string $class)
 * @method static Annotation[] propertyAnnotations(string $class, string $property)
 * @method static Annotation[] propertiesAnnotations(string $class)
 * @method static Annotation[] methodAnnotations(string $class, string $method)
 * @method static Annotation[] methodsAnnotations(string $class)
 * @method static Annotation[] functionAnnotations(string $function)
 * @method static ReflectionClass getClassReflection(string $class)
 * @method static ReflectionProperty getPropertyReflection(string $class, string $property)
 * @method static ReflectionMethod getMethodReflection(string $class, string $method)
 * @method static ReflectionFunction getFunctionReflection(string $function)
 */
class Annotator extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return Valkyrja::app()->annotator();
    }
}