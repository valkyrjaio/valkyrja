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

namespace Valkyrja\Bin\Env;

use Valkyrja\Application\Constant\ComponentClass;
use Valkyrja\Application\Env\Env as ApplicationEnv;
use Valkyrja\Application\Provider\Provider;
use Valkyrja\Bin\Provider\ComponentProvider;

class Env extends ApplicationEnv
{
    /************************************************************
     *
     * Application component env variables.
     *
     ************************************************************/

    /** @var bool */
    public const bool APP_DEBUG_MODE = true;
    /** @var class-string<Provider>[] */
    public const array APP_REQUIRED_COMPONENTS = [
        ComponentClass::ATTRIBUTE,
        ComponentClass::CONTAINER,
        ComponentClass::DISPATCHER,
        ComponentClass::REFLECTION,
    ];
    /** @var class-string<Provider>[] */
    public const array APP_CORE_COMPONENTS = [
        ComponentClass::CLI_INTERACTION,
        ComponentClass::CLI_MIDDLEWARE,
        ComponentClass::CLI_ROUTING,
        ComponentClass::CLI_SERVER,
        ComponentClass::EVENT,
    ];
    /** @var class-string<Provider>[] */
    public const array APP_COMPONENTS = [
        ComponentProvider::class,
    ];
}
