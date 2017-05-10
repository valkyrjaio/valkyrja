<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\View;

use Twig_Environment;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\View\TwigView as TwigViewContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Support\Directory;

/**
 * Class TwigView.
 *
 *
 * @author  Melech Mizrachi
 */
class TwigView extends View implements TwigViewContract
{
    /**
     * The twig environment.
     *
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * View constructor.
     *
     * @param \Valkyrja\Contracts\Application $app       The application
     * @param string                          $template  [optional] The template to set
     * @param array                           $variables [optional] The variables to set
     */
    public function __construct(Application $app, string $template = '', array $variables = [])
    {
        parent::__construct($app, $template, $variables);

        $this->fileExtension = '.twig';
    }

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function make(string $template = '', array $variables = []): ViewContract
    {
        /** @var TwigViewContract $view */
        $view = parent::make($template, $variables);

        $view->setTwig($this->twig);

        return $view;
    }

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getTemplateDir(string $path = null): string
    {
        return $path
            ?: Directory::DIRECTORY_SEPARATOR;
    }

    /**
     * Set the twig environment.
     *
     * @param Twig_Environment $twig The twig environment
     *
     * @return \Valkyrja\Contracts\View\TwigView
     */
    public function setTwig(Twig_Environment $twig): TwigViewContract
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * Render the templates and view.
     *
     * @param array $variables [optional] The variables to set
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     *
     * @return string
     */
    public function render(array $variables = []): string
    {
        return $this->twig->render($this->getTemplatePath(), $this->variables);
    }
}
