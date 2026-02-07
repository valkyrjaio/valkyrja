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

namespace Valkyrja\Tests\Classes\Container\Provider;

use Override;
use Valkyrja\Container\Provider\Contract\ProviderContract;

/**
 * Class ProviderClass.
 */
final class ProviderClass implements ProviderContract
{
    public static bool $publishCalled = false;

    public static bool $publishSecondaryCalled = false;

    #[Override]
    public static function deferred(): bool
    {
        return false;
    }

    #[Override]
    public static function publishers(): array
    {
        return [
            ProvidedSecondaryClass::class => [self::class, 'publishSecondary'],
        ];
    }

    #[Override]
    public static function provides(): array
    {
        return [
            ProvidedClass::class,
            ProvidedSecondaryClass::class,
        ];
    }

    #[Override]
    public static function publish(object $providerAware): void
    {
        self::$publishCalled = true;
    }

    public static function publishSecondary(object $providerAware): void
    {
        self::$publishSecondaryCalled = true;
    }
}
