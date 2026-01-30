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

namespace Valkyrja\Cli\Routing\Constant;

use Valkyrja\Cli\Interaction\Message\Message;
use Valkyrja\Cli\Interaction\Message\Messages;
use Valkyrja\Cli\Interaction\Message\NewLine;
use Valkyrja\Cli\Routing\Data\ArgumentParameter;
use Valkyrja\Cli\Routing\Data\OptionParameter;
use Valkyrja\Cli\Routing\Data\Route;
use Valkyrja\Dispatch\Data\MethodDispatch;

final class AllowedClasses
{
    /** @var class-string[] */
    public const array COLLECTION = [
        Route::class,
        Message::class,
        Messages::class,
        NewLine::class,
        MethodDispatch::class,
        ArgumentParameter::class,
        OptionParameter::class,
    ];
}
