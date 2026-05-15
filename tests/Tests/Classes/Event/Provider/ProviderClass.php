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

namespace Valkyrja\Tests\Classes\Event\Provider;

use Override;
use Valkyrja\Event\Provider\Contract\ListenerProviderContract;

final class ProviderClass implements ListenerProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    public function getListenerClasses(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getListeners(): array
    {
        return [];
    }
}
