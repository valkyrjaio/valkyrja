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

namespace Valkyrja\Http\Message\Throwable\Exception;

use Valkyrja\Http\Message\Throwable\Contract\Throwable;

/**
 * Class RuntimeException.
 *
 * @author Melech Mizrachi
 */
class RuntimeException extends \Valkyrja\Http\Throwable\Exception\RuntimeException implements Throwable
{
}
