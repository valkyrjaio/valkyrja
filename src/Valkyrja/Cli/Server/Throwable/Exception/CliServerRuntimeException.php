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

namespace Valkyrja\Cli\Server\Throwable\Exception;

use Valkyrja\Cli\Server\Throwable\Contract\CliServerThrowable;
use Valkyrja\Cli\Throwable\Exception\CliRuntimeException;

class CliServerRuntimeException extends CliRuntimeException implements CliServerThrowable
{
}
