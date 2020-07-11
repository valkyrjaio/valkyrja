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

namespace Valkyrja\Mail\Facades;

use Valkyrja\Mail\Mail as Contract;
use Valkyrja\Mail\Message;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Mail.
 *
 * @author Melech Mizrachi
 *
 * @method static Message createMessage()
 */
class Mail extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return self::$container->getSingleton(Contract::class);
    }
}
