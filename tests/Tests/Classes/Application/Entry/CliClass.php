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

namespace Valkyrja\Tests\Classes\Application\Entry;

use Valkyrja\Application\Entry\Cli;
use Valkyrja\Application\Env\Env;
use Valkyrja\Cli\Interaction\Input\Contract\InputContract;

final class CliClass extends Cli
{
    /**
     * Wrapper to test the getInput method directly.
     */
    public static function getInputExposed(Env $env): InputContract
    {
        return self::getInput($env);
    }
}
