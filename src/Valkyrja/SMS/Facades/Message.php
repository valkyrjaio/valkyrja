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

namespace Valkyrja\SMS\Facades;

use Valkyrja\SMS\Message as Contract;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Message.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract make()
 * @method static Contract setTo(string $to)
 * @method static Contract setFrom(string $from)
 * @method static Contract setText(string $text)
 * @method static Contract setUnicodeText(string $unicodeText)
 * @method static bool send()
 */
class Message extends Facade
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
