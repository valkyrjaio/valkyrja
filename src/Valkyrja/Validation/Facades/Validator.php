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

namespace Valkyrja\Validation\Facades;

use Valkyrja\Support\Facade\Facade;
use Valkyrja\Validation\Validator as Contract;

/**
 * Class Validator.
 *
 * @author Melech Mizrachi
 *
 * @method static mixed getRules(string $name = null)
 * @method static bool validate()
 * @method static bool validateRules(array ...$rules)
 * @method static void setRules(array ...$rules)
 * @method static string|null getErrorMessage()
 * @method static void setDefaultErrorMessage(string $defaultErrorMessage)
 */
class Validator extends Facade
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
