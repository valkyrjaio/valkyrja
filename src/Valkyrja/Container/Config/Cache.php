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

namespace Valkyrja\Container\Config;

use Valkyrja\Config\Config as Model;
use Valkyrja\Container\Contract\Service;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache extends Model
{
    /**
     * The aliases.
     *
     * @var string[]
     */
    public array $aliases;

    /**
     * The context services.
     *
     * @var string[]
     */
    public array $contextServices;

    /**
     * The deferred services.
     *
     * @var string[]
     */
    public array $deferred;

    /**
     * The deferred services' publish methods.
     *
     * @var array<string, callable>
     */
    public array $deferredCallback;

    /**
     * The services.
     *
     * @var class-string<Service>[]
     */
    public array $services;

    /**
     * The singletons.
     *
     * @var string[]
     */
    public array $singletons;
}
