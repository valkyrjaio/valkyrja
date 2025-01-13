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

namespace Valkyrja\Http\Message\Header\Exception;

use Valkyrja\Http\Message\Exception\RuntimeException as ParentException;

/**
 * Class RuntimeException.
 *
 * @author Melech Mizrachi
 */
class RuntimeException extends ParentException implements Throwable
{
}
