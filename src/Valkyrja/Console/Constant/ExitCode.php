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

namespace Valkyrja\Console\Constant;

/**
 * Enum ExitCode.
 *
 * @author Melech Mizrachi
 */
final class ExitCode
{
    public const int SUCCESS   = 0;
    public const int FAILURE   = 1;
    public const int AUTO_EXIT = 255;
}
