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
use Valkyrja\Event\Data\Listener;
use Valkyrja\Event\Provider\Provider;

final class ListenerProviderClass extends Provider
{
    #[Override]
    public static function getListeners(): array
    {
        return [
            new Listener(eventId: self::class, name: 'listener-from-provider-name'),
        ];
    }
}
