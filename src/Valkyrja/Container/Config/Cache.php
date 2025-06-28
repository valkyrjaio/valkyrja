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

use Valkyrja\Container\Contract\Service;

/**
 * Class Cache.
 *
 * @author Melech Mizrachi
 */
class Cache
{
    /**
     * The aliases.
     *
     * @var array<class-string, class-string>
     */
    public array $aliases;

    /**
     * The context services.
     *
     * @var array<class-string, class-string>
     */
    public array $contextServices;

    /**
     * The deferred services.
     *
     * @var array<class-string, class-string>
     */
    public array $deferred;

    /**
     * The deferred services' publish methods.
     *
     * @var array<class-string, callable>
     */
    public array $deferredCallback;

    /**
     * The services.
     *
     * @var array<class-string<Service>, class-string<Service>>
     */
    public array $services;

    /**
     * The singletons.
     *
     * @var array<class-string, class-string>
     */
    public array $singletons;
}
