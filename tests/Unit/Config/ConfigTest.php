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

namespace Valkyrja\Tests\Unit\Config;

use Valkyrja\Tests\Classes\Config\ConfigClass;
use Valkyrja\Tests\Classes\Config\ConfigClassAfter;
use Valkyrja\Tests\Classes\Config\ConfigClassBefore;
use Valkyrja\Tests\Classes\Env\EmptyEnvClass;
use Valkyrja\Tests\Classes\Env\EnvClass;
use Valkyrja\Tests\Classes\Env\EnvClassWithCallable;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the abstract config.
 *
 * @author Melech Mizrachi
 */
class ConfigTest extends TestCase
{
    public function testWithNoEnv(): void
    {
        $config = new ConfigClass();

        self::assertSame('public', $config->public);
        self::assertNull($config->nullable);

        $config = ConfigClassBefore::fromEnv(EmptyEnvClass::class);

        self::assertSame(ConfigClassBefore::PUBLIC, $config->public);
        self::assertSame(ConfigClassBefore::NULLABLE, $config->nullable);

        $config = ConfigClassAfter::fromEnv(EmptyEnvClass::class);

        self::assertSame(ConfigClassAfter::PUBLIC, $config->public);
        self::assertSame(ConfigClassAfter::NULLABLE, $config->nullable);
    }

    public function testWithEmptyEnv(): void
    {
        $config = ConfigClass::fromEnv(EmptyEnvClass::class);

        self::assertSame('public', $config->public);
        self::assertNull($config->nullable);

        $config = ConfigClassBefore::fromEnv(EmptyEnvClass::class);

        self::assertSame(ConfigClassBefore::PUBLIC, $config->public);
        self::assertSame(ConfigClassBefore::NULLABLE, $config->nullable);

        $config = ConfigClassAfter::fromEnv(EmptyEnvClass::class);

        self::assertSame(ConfigClassAfter::PUBLIC, $config->public);
        self::assertSame(ConfigClassAfter::NULLABLE, $config->nullable);
    }

    public function testWithEnv(): void
    {
        $config = ConfigClass::fromEnv(EnvClass::class);

        self::assertSame(EnvClass::DATA_CONFIG_PUBLIC, $config->public);
        self::assertSame(EnvClass::DATA_CONFIG_NULLABLE, $config->nullable);

        $config = ConfigClassBefore::fromEnv(EnvClass::class);

        self::assertSame(EnvClass::DATA_CONFIG_PUBLIC, $config->public);
        self::assertSame(EnvClass::DATA_CONFIG_NULLABLE, $config->nullable);

        $config = ConfigClassAfter::fromEnv(EnvClass::class);

        self::assertSame(ConfigClassAfter::PUBLIC, $config->public);
        self::assertSame(ConfigClassAfter::NULLABLE, $config->nullable);
    }

    public function testWithCallableEnv(): void
    {
        $config = ConfigClass::fromEnv(EnvClassWithCallable::class);

        self::assertSame(EnvClassWithCallable::getDataConfigPublic(), $config->public);
        self::assertNull($config->nullable);
    }
}
