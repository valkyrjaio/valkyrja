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

use Valkyrja\Application\Env\Env;
use Valkyrja\Filesystem\Manager\Contract\FilesystemContract;
use Valkyrja\Filesystem\Manager\InMemoryFilesystem;

/**
 * Class EnvClass.
 */
class EnvClass extends Env
{
    /************************************************************
     *
     * Application component env variables.
     *
     ************************************************************/

    /** @var non-empty-string */
    public const string APP_DIR = __DIR__ . '/../';

    /************************************************************
     *
     * Filesystem component env variables.
     *
     ************************************************************/

    /** @var class-string<FilesystemContract> */
    public const string FILESYSTEM_DEFAULT = InMemoryFilesystem::class;

    /************************************************************
     *
     * View component env variables.
     *
     ************************************************************/

    /** @var non-empty-string */
    public const string VIEW_ORKA_PATH = '/storage';
    /** @var non-empty-string */
    public const string VIEW_PHP_PATH = '/storage';
    /** @var non-empty-string */
    public const string VIEW_TWIG_COMPILED_PATH = '/storage';
}
