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

use Valkyrja\Console\Input as Contract;
use Valkyrja\Console\Inputs\Option;
use Valkyrja\Support\Facade\Facade;

/**
 * Class Input.
 *
 * @author Melech Mizrachi
 *
 * @method static array getArguments()
 * @method static array getShortOptions()
 * @method static array getLongOptions()
 * @method static array getOptions()
 * @method static string getStringArguments()
 * @method static array getRequestArguments()
 * @method static string|null getArgument(string $argument)
 * @method static bool hasArgument(string $argument)
 * @method static string|null getShortOption(string $option)
 * @method static bool hasShortOption(string $option)
 * @method static string|null getLongOption(string $option)
 * @method static bool hasLongOption(string $option)
 * @method static string|null getOption(string $option)
 * @method static bool hasOption(string $option)
 * @method static Option[] getGlobalOptions()
 * @method static string[] getGlobalOptionsFlat()
 */
class Input extends Facade
{
    /**
     * @inheritDoc
     */
    public static function instance(): object|string
    {
        return self::$container->getSingleton(Contract::class);
    }
}
