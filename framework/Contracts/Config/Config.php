<?php

namespace Valkyrja\Contracts\Config;

use Valkyrja\Contracts\Application;

interface Config
{
    /**
     * Config constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app);

    /**
     * Get an environment variable.
     *
     * @param string $key
     *
     * @return mixed
     */
    public static function env(string $key); // : mixed;
}
