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

namespace Valkyrja\Cli\Interaction\Throwable\Exception;

use Valkyrja\Cli\Interaction\Throwable\Contract\Throwable;
use Valkyrja\Cli\Throwable\Exception\InvalidArgumentException as CliInvalidArgumentException;

/**
 * Class InvalidArgumentException.
 */
class InvalidArgumentException extends CliInvalidArgumentException implements Throwable
{
}
