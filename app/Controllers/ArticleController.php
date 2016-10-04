<?php

namespace App\Controllers;

use App\Models\Article;
use Valkyrja\Contracts\View\View;

class ArticleController extends Controller
{
    /**
     * @var Article
     */
    protected $article;

    /**
     * ArticleController constructor.
     *
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Single article action.
     *
     * @param string $slug
     *
     * @return View
     */
    public function index($slug)
    {
        return response()->view('index', ['slug' => $slug]);
    }
}
