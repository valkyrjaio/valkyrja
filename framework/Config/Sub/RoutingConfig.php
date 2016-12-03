<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Sub;

use Valkyrja\Contracts\Application;
use Valkyrja\Support\Helpers;

/**
 * Class RoutingConfig
 *
 * @package Valkyrja\Config\Sub
 */
class RoutingConfig
{
    /**
     * Use array arguments.
     *
     * @var bool
     */
    public $useArrayArgs = false;

    /**
     * Set defaults?
     *
     * @var bool
     */
    protected $setDefaults = true;

    /**
     * RoutingConfig constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        if ($this->setDefaults) {
            $this->useArrayArgs = Helpers::env('ROUTING_USE_ARRAY_ARGS') ?? false;
        }
    }
}
