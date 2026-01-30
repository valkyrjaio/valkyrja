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

namespace Valkyrja\Event\Constant;

use Valkyrja\Dispatch\Data\ClassDispatch;
use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Event\Data\Listener;

final class AllowedClasses
{
    public const array COLLECTION = [
        Listener::class,
        ClassDispatch::class,
        MethodDispatch::class,
    ];
}
