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

namespace Valkyrja\Container\Provider;

use Override;
use Valkyrja\Container\Provider\Contract\ProviderContract;

abstract class Provider implements ProviderContract
{
    /**
     * @inheritDoc
     */
    #[Override]
    abstract public static function publishers(): array;

    /**
     * @inheritDoc
     */
    #[Override]
    abstract public static function provides(): array;
}
