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

namespace Valkyrja\Tests\Fixtures\Application\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Data\Contract\HttpConfigContract;

/**
 * The base application config, handed back as the protocol config an entry point asks
 * for.
 *
 * An entry point declares the protocol config it needs and lets PHP's own type check
 * reject anything else. Reaching that check needs the base config in a protocol
 * config's place, which is the one thing this fixture provides.
 */
final class BaseConfigFixture
{
    public static function asHttpConfig(): HttpConfigContract
    {
        /** @var HttpConfigContract $config */
        $config = self::baseConfig();

        return $config;
    }

    public static function asCliConfig(): CliConfigContract
    {
        /** @var CliConfigContract $config */
        $config = self::baseConfig();

        return $config;
    }

    /**
     * @return object The base config, typed loosely so it can stand in above
     */
    private static function baseConfig(): object
    {
        return new Config();
    }
}
