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

/**
 * Class DeferredProviderClass.
 *
 * @author Melech Mizrachi
 */
class DeferredProviderClass extends ProviderClass
{
    public static bool $publishCalled = false;

    public static bool $publishSecondaryCalled = false;

    public static function deferred(): bool
    {
        return true;
    }
}
