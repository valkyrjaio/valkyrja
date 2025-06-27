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

use Valkyrja\Application\Env as AppEnv;
use Valkyrja\Tests\Classes\Controller\ControllerClass;

/**
 * Class EnvClass.
 *
 * @author Melech Mizrachi
 */
class EnvClass extends AppEnv
{
    /************************************************************
     *
     * Application component env variables.
     *
     ************************************************************/

    /** @var bool|null */
    public const bool|null APP_DEBUG_MODE = false;

    /************************************************************
     *
     * Config component env variables.
     *
     ************************************************************/

    /** @var string|null */
    public const string|null APP_CACHE_FILE_PATH = __DIR__ . '/bootstrap/cache.php';

    /************************************************************
     *
     * Cli Interaction component env variables.
     *
     ************************************************************/

    /** @var bool|null */
    public const bool|null CLI_INTERACTION_IS_QUIET = true;

    /************************************************************
     *
     * Http Routing component env variables.
     *
     ************************************************************/

    /** @var class-string[]|null */
    public const array|null HTTP_ROUTING_CONTROLLERS = [ControllerClass::class];
}
