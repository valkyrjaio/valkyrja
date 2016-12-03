<?php

namespace Valkyrja\Contracts\Config;

use Valkyrja\Config\Env;
use Valkyrja\Contracts\Application;

interface Config
{
    /**
     * Config constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app);
}
