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

namespace Valkyrja\Console\Facades;

use Valkyrja\Console\Enums\OutputStyle;
use Valkyrja\Console\Formatter;
use Valkyrja\Console\Output as Contract;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Output.
 *
 * @author Melech Mizrachi
 *
 * @method static void setFormatter(Formatter $formatter)
 * @method static void write(array $messages, bool $newLine = null, OutputStyle $outputStyle = null)
 * @method static void writeMessage(string $message, bool $newLine = null, OutputStyle $outputStyle = null)
 */
class Output extends Facade
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
