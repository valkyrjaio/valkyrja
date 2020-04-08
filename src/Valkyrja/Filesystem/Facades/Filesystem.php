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

namespace Valkyrja\Filesystem\Facades;

use Valkyrja\Facade\Facades\Facade;
use Valkyrja\Filesystem\Adapter;
use Valkyrja\Filesystem\Enums\Visibility;

/**
 * Class Filesystem.
 *
 * @author Melech Mizrachi
 *
 * @method static bool exists(string $path)
 * @method static string|null read(string $path)
 * @method static bool write(string $path, string $contents)
 * @method static bool writeStream(string $path, $resource)
 * @method static bool update(string $path, string $contents)
 * @method static bool updateStream(string $path, $resource)
 * @method static bool put(string $path, string $contents)
 * @method static bool putStream(string $path, $resource)
 * @method static bool rename(string $path, string $newPath)
 * @method static bool copy(string $path, string $newPath)
 * @method static bool delete(string $path)
 * @method static array|null metadata(string $path)
 * @method static string|null mimetype(string $path)
 * @method static int|null size(string $path)
 * @method static int|null timestamp(string $path)
 * @method static bool setVisibility(string $path, Visibility $visibility)
 * @method static bool setVisibilityPublic(string $path)
 * @method static bool setVisibilityPrivate(string $path)
 * @method static bool createDir(string $path)
 * @method static bool deleteDir(string $path)
 * @method static array listContents(string $directory = null, bool $recursive = false)
 * @method static Adapter getAdapter(string $adapter)
 * @method static Adapter local()
 * @method static Adapter s3()
 */
class Filesystem extends Facade
{
    /**
     * The facade instance.
     *
     * @return string|object
     */
    public static function instance()
    {
        return \Valkyrja\filesystem();
    }
}
