<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Mail\Facades;

use Valkyrja\Application\Applications\Valkyrja;
use Valkyrja\Facade\Facades\Facade;
use Valkyrja\Mail\Mail as Contract;

/**
 * Class Mail.
 *
 * @author Melech Mizrachi
 *
 * @method static bool setFrom(string $address, string $name = '')
 * @method static bool addAddress(string $address, string $name = '')
 * @method static bool addReplyTo(string $address, string $name = '')
 * @method static bool addCC(string $address, string $name = '')
 * @method static bool addBCC(string $address, string $name = '')
 * @method static bool addAttachment(string $path, string $name = '')
 * @method static Contract setSubject(string $subject)
 * @method static Contract setBody(string $body)
 * @method static Contract setPlainBody(string $body)
 * @method static bool send()
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
        return Valkyrja::app()->mail();
    }
}
