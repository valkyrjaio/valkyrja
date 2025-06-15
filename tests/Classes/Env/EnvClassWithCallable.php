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

namespace Valkyrja\Tests\Classes\Env;

/**
 * Class EnvClassWithCallable.
 *
 * @author Melech Mizrachi
 */
class EnvClassWithCallable extends EnvClass
{
    /** @var string|array{0: class-string, 1: string} */
    public const array|string DATA_CONFIG_PUBLIC = [self::class, 'getDataConfigPublic'];
    /** @var string|array{0: class-string, 1: string} */
    public const array|string DATA_CONFIG_NULLABLE = [self::class, 'getDataConfigNullable'];

    public static function getDataConfigPublic(): string
    {
        return 'publicFromEnvCallable';
    }

    public static function getDataConfigNullable(): string|null
    {
        return null;
    }
}
