<?php

namespace config\sub;

use App\Providers\AppServiceProvider;
use Valkyrja\Config\Sub\ContainerConfig as ValkyrjaContainerConfig;
use Valkyrja\Contracts\Config\Env;
use Valkyrja\Providers\TwigServiceProvider;

/**
 * Class ContainerConfig
 *
 * @package config\sub
 */
class ContainerConfig extends ValkyrjaContainerConfig
{
    /**
     * ContainerConfig constructor.
     *
     * @param \Valkyrja\Contracts\Config\Env $env
     */
    public function __construct(Env $env)
    {
        parent::__construct($env);

        $this->providers = $env::CONTAINER_PROVIDERS
            ?? [
                AppServiceProvider::class,
                TwigServiceProvider::class,
            ];
    }
}
