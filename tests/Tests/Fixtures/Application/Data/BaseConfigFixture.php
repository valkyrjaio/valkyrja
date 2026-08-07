<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Tests\Fixtures\Application\Data;

use Valkyrja\Application\Data\Config;
use Valkyrja\Application\Data\Contract\CliConfigContract;
use Valkyrja\Application\Data\Contract\HttpConfigContract;

/**
 * The base application config, handed back as the protocol config an entry point asks for.
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
