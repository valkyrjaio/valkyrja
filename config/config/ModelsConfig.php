<?php

namespace config\config;

use App\Models\Article;
use App\Models\User;

use config\Configs;

use Valkyrja\Application;

class ModelsConfig
{
    /**
     * The article model to use.
     *
     * @var string
     */
    public $article;

    /**
     * The user model to use.
     *
     * @var string
     */
    public $user;

    /**
     * AppConfig constructor.
     *
     * @param \Valkyrja\Application $app
     */
    public function __construct(Application $app)
    {
        $this->article = Configs::env('MODELS_ARTICLE') ?? Article::class;
        $this->user = Configs::env('MODELS_USER') ?? User::class;
    }
}
