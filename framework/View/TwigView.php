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
     * @var string
     */
    protected $fileExtension = '.twig';

    /**
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * @inheritdoc
     */
    public function make($template = '', array $variables = [])
    {
        $view = new static($template, $variables);

        $view->setTwig($this->twig);

        return $view;
    }

    /**
     * @inheritdoc
     */
    public function getTemplateDir($path = null)
    {
        return $path
            ?: '/';
    }

    /**
     * @inheritdoc
     */
    public function setTwig(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @inheritdoc
     */
    public function render(array $variables = [])
    {
        return $this->twig->render($this->getTemplatePath(), $this->variables);
    }
}
