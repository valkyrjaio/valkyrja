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

namespace Valkyrja\Tests\Classes\Application\Provider;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ComponentProviderContract;
use Valkyrja\Tests\Classes\Event\Provider\ListenerProviderClass;

/**
 * A component provider that intentionally returns the same sub-providers as
 * existing test providers, so that deduplication tests can verify array_unique
 * eliminates the duplicates.
 */
final class DuplicateSubProviderClass implements ComponentProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getComponentProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getContainerProviders(ApplicationContract $app): array
    {
        return [
            CliContainerDataProviderClass::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getEventProviders(ApplicationContract $app): array
    {
        return [
            ListenerProviderClass::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getCliProviders(ApplicationContract $app): array
    {
        return [
            CliRouteProviderClass::class,
        ];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getHttpProviders(ApplicationContract $app): array
    {
        return [
            HttpRouteProviderClass::class,
        ];
    }
}
