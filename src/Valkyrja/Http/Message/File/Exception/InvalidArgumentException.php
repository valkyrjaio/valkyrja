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

namespace Valkyrja\Http\Message\File\Exception;

use Valkyrja\Http\Message\Exception\InvalidArgumentException as ParentException;

/**
 * Class InvalidArgumentException.
 *
 * @author Melech Mizrachi
 */
class InvalidArgumentException extends ParentException implements Throwable
{
}
