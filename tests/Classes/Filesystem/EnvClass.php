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

namespace Valkyrja\Tests\Classes\Filesystem;

use Valkyrja\Application\Env\Env as AppEnv;

/**
 * Class EnvClass.
 *
 * @author Melech Mizrachi
 */
class EnvClass extends AppEnv
{
    /************************************************************
     *
     * Filesystem component env variables.
     *
     ************************************************************/

    /** @var non-empty-string */
    public const string FILESYSTEM_FLYSYSTEM_LOCAL_DIR = __DIR__ . '/../../storage/app';
}
