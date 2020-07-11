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

use Valkyrja\Mail\Message as Contract;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Message.
 *
 * @author Melech Mizrachi
 *
 * @method static Contract make()
 * @method static Contract setFrom(string $address, string $name = '')
 * @method static Contract addAddress(string $address, string $name = '')
 * @method static Contract addReplyTo(string $address, string $name = '')
 * @method static Contract addCC(string $address, string $name = '')
 * @method static Contract addBCC(string $address, string $name = '')
 * @method static Contract addAttachment(string $path, string $name = '')
 * @method static Contract setSubject(string $subject)
 * @method static Contract setBody(string $body)
 * @method static Contract setPlainBody(string $body)
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
