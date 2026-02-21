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

namespace Valkyrja\Event\Provider;

use Override;
use Valkyrja\Event\Provider\Contract\ProviderContract;

abstract class Provider implements ProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public static function getListenerClasses(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public static function getListeners(): array
    {
        return [];
    }
}
