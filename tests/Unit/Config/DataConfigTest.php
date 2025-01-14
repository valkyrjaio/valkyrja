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

use RuntimeException;
use Valkyrja\Tests\Classes\Config\DataConfigClass;
use Valkyrja\Tests\Classes\Config\DataConfigClassAfter;
use Valkyrja\Tests\Classes\Config\DataConfigClassBefore;
use Valkyrja\Tests\Classes\Env\EmptyEnvClass;
use Valkyrja\Tests\Classes\Env\EnvClass;
use Valkyrja\Tests\Unit\TestCase;

/**
 * Test the abstract config.
 *
 * @author Melech Mizrachi
 */
class DataConfigTest extends TestCase
{
    public function testWithNoEnv(): void
    {
        $config = new DataConfigClass();

        self::assertSame('public', $config->public);
        self::assertNull($config->nullable);

        $config = DataConfigClassBefore::fromEnv(EmptyEnvClass::class);

        self::assertSame(DataConfigClassBefore::PUBLIC, $config->public);
        self::assertSame(DataConfigClassBefore::NULLABLE, $config->nullable);

        $config = DataConfigClassAfter::fromEnv(EmptyEnvClass::class);

        self::assertSame(DataConfigClassAfter::PUBLIC, $config->public);
        self::assertSame(DataConfigClassAfter::NULLABLE, $config->nullable);
    }

    public function testWithEmptyEnv(): void
    {
        $config = DataConfigClass::fromEnv(EmptyEnvClass::class);

        self::assertSame('public', $config->public);
        self::assertNull($config->nullable);

        $config = DataConfigClassBefore::fromEnv(EmptyEnvClass::class);

        self::assertSame(DataConfigClassBefore::PUBLIC, $config->public);
        self::assertSame(DataConfigClassBefore::NULLABLE, $config->nullable);

        $config = DataConfigClassAfter::fromEnv(EmptyEnvClass::class);

        self::assertSame(DataConfigClassAfter::PUBLIC, $config->public);
        self::assertSame(DataConfigClassAfter::NULLABLE, $config->nullable);
    }

    public function testWithEnv(): void
    {
        $config = DataConfigClass::fromEnv(EnvClass::class);

        self::assertSame(EnvClass::DATA_CONFIG_PUBLIC, $config->public);
        self::assertSame(EnvClass::DATA_CONFIG_NULLABLE, $config->nullable);

        $config = DataConfigClassBefore::fromEnv(EnvClass::class);

        self::assertSame(EnvClass::DATA_CONFIG_PUBLIC, $config->public);
        self::assertSame(EnvClass::DATA_CONFIG_NULLABLE, $config->nullable);

        $config = DataConfigClassAfter::fromEnv(EnvClass::class);

        self::assertSame(DataConfigClassAfter::PUBLIC, $config->public);
        self::assertSame(DataConfigClassAfter::NULLABLE, $config->nullable);
    }

    public function testOffsetExists(): void
    {
        $config = new DataConfigClass();

        self::assertTrue(isset($config['public']));
        self::assertFalse(isset($config['nullable']));
        self::assertTrue($config->offsetExists('public'));
        self::assertFalse($config->offsetExists('nullable'));

        $config = DataConfigClassBefore::fromEnv(EmptyEnvClass::class);

        self::assertTrue(isset($config['public']));
        self::assertTrue(isset($config['nullable']));
        self::assertTrue($config->offsetExists('public'));
        self::assertTrue($config->offsetExists('nullable'));

        $config = DataConfigClassAfter::fromEnv(EmptyEnvClass::class);

        self::assertTrue(isset($config['public']));
        self::assertTrue(isset($config['nullable']));
        self::assertTrue($config->offsetExists('public'));
        self::assertTrue($config->offsetExists('nullable'));
    }

    public function testOffsetGet(): void
    {
        $config = new DataConfigClass();

        self::assertSame('public', $config['public']);
        self::assertNull($config['nullable']);
        self::assertSame('public', $config->offsetGet('public'));
        self::assertNull($config->offsetGet('nullable'));

        $config = DataConfigClassBefore::fromEnv(EmptyEnvClass::class);

        self::assertSame(DataConfigClassBefore::PUBLIC, $config->offsetGet('public'));
        self::assertSame(DataConfigClassBefore::NULLABLE, $config->offsetGet('nullable'));

        $config = DataConfigClassAfter::fromEnv(EmptyEnvClass::class);

        self::assertSame(DataConfigClassAfter::PUBLIC, $config->offsetGet('public'));
        self::assertSame(DataConfigClassAfter::NULLABLE, $config->offsetGet('nullable'));
    }

    public function testOffsetSet(): void
    {
        $config             = new DataConfigClass();
        $config['public']   = 'test';
        $config['nullable'] = 'test';

        self::assertSame('test', $config['public']);
        self::assertSame('test', $config['nullable']);

        $config->offsetSet('public', 'test2');
        $config->offsetSet('nullable', 'test2');

        self::assertSame('test2', $config['public']);
        self::assertSame('test2', $config['nullable']);
    }

    public function testOffsetUnset(): void
    {
        $this->expectException(RuntimeException::class);

        $config             = new DataConfigClass();
        $config['public']   = 'test';
        $config['nullable'] = 'test';

        unset($config['public'], $config['nullable']);
    }
}
