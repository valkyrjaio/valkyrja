<?php

namespace config\sub;

use App\Models\User;

use config\Config;

use Valkyrja\Contracts\Application;

/**
 * Class ModelsConfig
 *
 * @package config\sub
 */
class ModelsConfig
{
    /**
     * The user model to use.
     *
     * @var string
     */
    public $user;

    /**
     * AppConfig constructor.
     *
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        $this->user = Config::env('MODELS_USER') ?? User::class;
    }
}
