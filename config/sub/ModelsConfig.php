<?php

namespace config\sub;

use App\Models\Article;
use App\Models\User;

use config\Config;

use Valkyrja\Contracts\Application;

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
     * @param \Valkyrja\Contracts\Application $app
     */
    public function __construct(Application $app)
    {
        $this->article = Config::env('MODELS_ARTICLE') ?? Article::class;
        $this->user = Config::env('MODELS_USER') ?? User::class;
    }
}
