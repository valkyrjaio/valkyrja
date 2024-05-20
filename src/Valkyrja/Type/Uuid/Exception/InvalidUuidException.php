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

namespace Valkyrja\Type\Uuid\Exception;

use Valkyrja\Type\Uid\Exception\InvalidUidException;

/**
 * Class InvalidUuidException.
 *
 * @author Melech Mizrachi
 */
class InvalidUuidException extends InvalidUidException implements UuidThrowable
{
}
