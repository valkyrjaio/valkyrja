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

namespace Valkyrja\Annotation\Facades;

use Valkyrja\Attribute\Attributes as Contract;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Attributes.
 *
 * @author Melech Mizrachi
 *
 * @method static object[] forClass(string $class, string $attribute = null)
 * @method static object[] forClassMembers(string $class, string $attribute = null)
 * @method static object[] forClassAndMembers(string $class, string $attribute = null)
 * @method static object[] forProperty(string $class, string $property, string $attribute = null)
 * @method static object[] forProperties(string $class, string $attribute = null)
 * @method static object[] forMethod(string $class, string $method, string $attribute = null)
 * @method static object[] forMethods(string $class, string $attribute = null)
 * @method static object[] forFunction(string $function, string $attribute = null)
 */
class Attributes extends Facade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}