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
use Valkyrja\Filesystem\Contract\Filesystem;
use Valkyrja\Filesystem\InMemoryFilesystem;

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

    /** @var bool */
    public const bool APP_DEBUG_MODE = false;

    /************************************************************
     *
     * Config component env variables.
     *
     ************************************************************/

    /** @var string */
    public const string APP_DIR = __DIR__;
    /** @var string */
    public const string APP_CACHE_FILE_PATH = __DIR__ . '/storage/cache.php';

    /************************************************************
     *
     * Cli Interaction component env variables.
     *
     ************************************************************/

    /** @var bool */
    public const bool CLI_INTERACTION_IS_QUIET = true;

    /************************************************************
     *
     * Filesystem component env variables.
     *
     ************************************************************/

    /** @var class-string<Filesystem> */
    public const string FILESYSTEM_DEFAULT = InMemoryFilesystem::class;
}
