<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Contracts\Config;

/**
 * Interface Config
 *
 * @package Valkyrja\Contracts\Config
 *
 * @author  Melech Mizrachi
 */
interface Config
{
    /**
     * Config constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env);
}
