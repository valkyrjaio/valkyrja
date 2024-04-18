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
use Valkyrja\Tests\Classes\Config\DataConfig;
use Valkyrja\Tests\Classes\Config\DataConfigAfter;
use Valkyrja\Tests\Classes\Config\DataConfigBefore;
use Valkyrja\Tests\Classes\Env\EmptyEnv;
use Valkyrja\Tests\Classes\Env\Env;
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
        $config = new DataConfig();

        self::assertSame('public', $config->public);
        self::assertNull($config->nullable);

        $config = DataConfigBefore::fromEnv(EmptyEnv::class);

        self::assertSame(DataConfigBefore::PUBLIC, $config->public);
        self::assertSame(DataConfigBefore::NULLABLE, $config->nullable);

        $config = DataConfigAfter::fromEnv(EmptyEnv::class);

        self::assertSame(DataConfigAfter::PUBLIC, $config->public);
        self::assertSame(DataConfigAfter::NULLABLE, $config->nullable);
    }

    public function testWithEmptyEnv(): void
    {
        $config = DataConfig::fromEnv(EmptyEnv::class);

        self::assertSame('public', $config->public);
        self::assertNull($config->nullable);

        $config = DataConfigBefore::fromEnv(EmptyEnv::class);

        self::assertSame(DataConfigBefore::PUBLIC, $config->public);
        self::assertSame(DataConfigBefore::NULLABLE, $config->nullable);

        $config = DataConfigAfter::fromEnv(EmptyEnv::class);

        self::assertSame(DataConfigAfter::PUBLIC, $config->public);
        self::assertSame(DataConfigAfter::NULLABLE, $config->nullable);
    }

    public function testWithEnv(): void
    {
        $config = DataConfig::fromEnv(Env::class);

        self::assertSame(Env::DATA_CONFIG_PUBLIC, $config->public);
        self::assertSame(Env::DATA_CONFIG_NULLABLE, $config->nullable);

        $config = DataConfigBefore::fromEnv(Env::class);

        self::assertSame(Env::DATA_CONFIG_PUBLIC, $config->public);
        self::assertSame(Env::DATA_CONFIG_NULLABLE, $config->nullable);

        $config = DataConfigAfter::fromEnv(Env::class);

        self::assertSame(DataConfigAfter::PUBLIC, $config->public);
        self::assertSame(DataConfigAfter::NULLABLE, $config->nullable);
    }

    public function testOffsetExists(): void
    {
        $config = new DataConfig();

        self::assertTrue(isset($config['public']));
        self::assertFalse(isset($config['nullable']));
        self::assertTrue($config->offsetExists('public'));
        self::assertFalse($config->offsetExists('nullable'));

        $config = DataConfigBefore::fromEnv(EmptyEnv::class);

        self::assertTrue(isset($config['public']));
        self::assertTrue(isset($config['nullable']));
        self::assertTrue($config->offsetExists('public'));
        self::assertTrue($config->offsetExists('nullable'));

        $config = DataConfigAfter::fromEnv(EmptyEnv::class);

        self::assertTrue(isset($config['public']));
        self::assertTrue(isset($config['nullable']));
        self::assertTrue($config->offsetExists('public'));
        self::assertTrue($config->offsetExists('nullable'));
    }

    public function testOffsetGet(): void
    {
        $config = new DataConfig();

        self::assertSame('public', $config['public']);
        self::assertNull($config['nullable']);
        self::assertSame('public', $config->offsetGet('public'));
        self::assertNull($config->offsetGet('nullable'));

        $config = DataConfigBefore::fromEnv(EmptyEnv::class);

        self::assertSame(DataConfigBefore::PUBLIC, $config->offsetGet('public'));
        self::assertSame(DataConfigBefore::NULLABLE, $config->offsetGet('nullable'));

        $config = DataConfigAfter::fromEnv(EmptyEnv::class);

        self::assertSame(DataConfigAfter::PUBLIC, $config->offsetGet('public'));
        self::assertSame(DataConfigAfter::NULLABLE, $config->offsetGet('nullable'));
    }

    public function testOffsetSet(): void
    {
        $config             = new DataConfig();
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

        $config             = new DataConfig();
        $config['public']   = 'test';
        $config['nullable'] = 'test';

        unset($config['public'], $config['nullable']);
    }
}
