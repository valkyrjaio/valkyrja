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

use Valkyrja\Contracts\View\TwigView as TwigViewContract;
use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Support\Helpers;

/**
 * Class TwigView
 *
 * @package Valkyrja\View
 *
 * @author  Melech Mizrachi
 */
class TwigView extends View implements TwigViewContract
{
    /**
     * The twig file extension.
     *
     * @var string
     */
    protected $fileExtension = '.twig';

    /**
     * The twig environment.
     *
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function make($template = '', array $variables = []) : ViewContract
    {
        $view = new static($template, $variables);

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
    public function getTemplateDir($path = null) : string
    {
        return $path
            ?: (Helpers::app())::DIRECTORY_SEPARATOR;
    }

    /**
     * Set the twig environment.
     *
     * @param Twig_Environment $twig The twig environment
     *
     * @return void
     */
    public function setTwig(Twig_Environment $twig) // : void
    {
        $this->twig = $twig;
    }

    /**
     * Render the templates and view.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(array $variables = []) : string
    {
        return $this->twig->render($this->getTemplatePath(), $this->variables);
    }
}
