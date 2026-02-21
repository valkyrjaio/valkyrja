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

namespace Valkyrja\Application\Provider;

use Override;
use Valkyrja\Application\Kernel\Contract\ApplicationContract;
use Valkyrja\Application\Provider\Contract\ProviderContract;

abstract class Provider implements ProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getContainerProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getEventProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getCliProviders(ApplicationContract $app): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getHttpProviders(ApplicationContract $app): array
    {
        return [];
    }
}
