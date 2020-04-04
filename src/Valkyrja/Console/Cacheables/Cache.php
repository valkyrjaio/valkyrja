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

namespace Valkyrja\Console\Cacheables;

use Valkyrja\Config\Config;

/**
 * Class CacheConfig.
 *
 * @author Melech Mizrachi
 */
class Cache extends Config
{
    /**
     * The base64 encoded serialized commands.
     *
     * @var string
     */
    public string $commands;

    /**
     * The command paths.
     *
     * @var array
     */
    public array $paths;

    /**
     * The named commands.
     *
     * @var array
     */
    public array $namedCommands;

    /**
     * The provided commands.
     *
     * @var array
     */
    public array $provided;
}
