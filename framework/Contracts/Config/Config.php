<?php

namespace Valkyrja\Contracts\Config;

use Valkyrja\Config\Env;
use Valkyrja\Contracts\Application;

interface Config
{
    /**
     * Which env file to use.
     *
     * @var string
     */
    const ENV_CLASS_NAME = Env::class;

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
