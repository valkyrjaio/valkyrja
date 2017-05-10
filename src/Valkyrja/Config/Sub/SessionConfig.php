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

use Valkyrja\Contracts\Config\Env;

/**
 * Class SessionConfig.
 *
 *
 * @author  Melech Mizrachi
 */
class SessionConfig
{
    /**
     * The session id.
     *
     * @var string
     */
    public $id;

    /**
     * The session name.
     *
     * @var string
     */
    public $name;

    /**
     * SessionConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        $this->id   = $env::SESSION_ID ?? $this->id;
        $this->name = $env::SESSION_NAME ?? $this->name;
    }
}
