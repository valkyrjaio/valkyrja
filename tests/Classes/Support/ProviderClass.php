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

namespace Valkyrja\Tests\Classes\Support;

use Valkyrja\Container\Provider\Contract\ProviderContract;

/**
 * Class ProviderClass.
 */
class ProviderClass implements ProviderContract
{
    public static bool $publishCalled = false;

    public static bool $publishSecondaryCalled = false;

    public static function deferred(): bool
    {
        return false;
    }

    public static function publishers(): array
    {
        return [
            ProvidedSecondaryClass::class => [static::class, 'publishSecondary'],
        ];
    }

    public static function provides(): array
    {
        return [
            ProvidedClass::class,
            ProvidedSecondaryClass::class,
        ];
    }

    public static function publish(object $providerAware): void
    {
        static::$publishCalled = true;
    }

    public static function publishSecondary(object $providerAware): void
    {
        static::$publishSecondaryCalled = true;
    }
}
