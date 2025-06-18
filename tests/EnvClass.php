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

namespace Valkyrja\Tests;

use Valkyrja\Application\Env;
use Valkyrja\Tests\Classes\Controller\ControllerClass;

/**
 * Class Env.
 *
 * @author Melech Mizrachi
 */
class EnvClass extends Env
{
    /************************************************************
     *
     * Application component env variables.
     *
     ************************************************************/

    /** @var bool|null */
    public const bool|null APP_DEBUG = false;

    /************************************************************
     *
     * Config component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null CONFIG_CACHE_FILE_PATH = __DIR__ . '/bootstrap/cache.php';

    /************************************************************
     *
     * Console component env variables.
     *
     ************************************************************/

    /** @var bool|null */
    public const bool|null CONSOLE_SHOULD_RUN_QUIETLY = true;

    /************************************************************
     *
     * Http Routing component env variables.
     *
     ************************************************************/

    /** @var class-string[]|null */
    public const array|null HTTP_ROUTING_CONTROLLERS = [ControllerClass::class];
}
