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

namespace Valkyrja\Http\Routing\Constant;

use Valkyrja\Dispatch\Data\MethodDispatch;
use Valkyrja\Http\Message\Enum\RequestMethod;
use Valkyrja\Http\Routing\Data\Parameter;
use Valkyrja\Http\Routing\Data\Route;

final class AllowedClasses
{
    /** @var class-string[] */
    public const array COLLECTION = [
        Route::class,
        Parameter::class,
        MethodDispatch::class,
        RequestMethod::class,
    ];
}
