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

use Valkyrja\Contracts\View\View as ViewContract;
use Valkyrja\Support\Helpers;

/**
 * Class View
 *
 * @package Valkyrja\View
 *
 * @author  Melech Mizrachi
 */
class View implements ViewContract
{
    /**
     * The master template.
     *
     * @var string
     */
    protected $masterTemplate = 'layout';

    /**
     * The body content template.
     *
     * @var
     */
    protected $template = 'index';

    /**
     * The template directory.
     *
     * @var string
     */
    protected $templateDir;

    /**
     * @var string
     */
    protected $fileExtension = '.php';

    /**
     * The view variables.
     *
     * @var array
     */
    protected $variables = [];

    /**
     * View constructor.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     */
    public function __construct(string $template = '', array $variables = [])
    {
        $this->setVariables($variables);
        $this->setTemplate($template);
        $this->setTemplateDir(Helpers::config()->views->dir ?? resourcesPath('views'));
    }

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function make(string $template = '', array $variables = []) : ViewContract
    {
        return new static($template, $variables);
    }

    /**
     * Set the master template.
     *
     * @param string $template The master template to set
     *
     * @return void
     */
    public function setMasterTemplate(string $template) // : void
    {
        $this->masterTemplate = $template;
    }

    /**
     * Set the template.
     *
     * @param string $template The template to set
     *
     * @return void
     */
    public function setTemplate(string $template) // : void
    {
        $this->template = $template;
    }

    /**
     * Set the variables
     *
     * @param array $variables [optional] The variables to set
     *
     * @return void
     */
    public function setVariables(array $variables = []) // : void
    {
        $this->variables = array_merge($this->variables, $variables);
    }

    /**
     * Set a single variable.
     *
     * @param string $key   The variable key to set
     * @param mixed  $value The value to set
     *
     * @return void
     */
    public function variable(string $key, $value) // : void
    {
        $this->variables[$key] = $value;
    }

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getTemplateDir(string $path = null) : string
    {
        return $this->templateDir . ($path
            ? app()::DIRECTORY_SEPARATOR . $path
            : $path);
    }

    /**
     * Set the template directory.
     *
     * @param string $templateDir The path to set
     *
     * @return void
     */
    public function setTemplateDir(string $templateDir) // : void
    {
        $this->templateDir = $templateDir;
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getFileExtension() : string
    {
        return $this->fileExtension;
    }

    /**
     * Set the file extension.
     *
     * @param string $extension The extension to set
     *
     * @return void
     */
    public function setFileExtension(string $extension) // : void
    {
        $this->fileExtension = $extension;
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath() : string
    {
        return $this->getTemplateDir($this->template . $this->getFileExtension());
    }

    /**
     * Get the master template path.
     *
     * @return string
     */
    public function getMasterTemplatePath() : string
    {
        return $this->getTemplateDir($this->masterTemplate . $this->getFileExtension());
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
        extract(array_merge($this->variables, $variables));

        ob_start();
        include $this->getTemplatePath();
        $view = ob_get_clean();

        if (! $this->masterTemplate || $this->masterTemplate === '') {
            return $view;
        }

        extract(['body' => $view]);

        ob_start();
        include $this->getMasterTemplatePath();
        $masterView = ob_get_clean();

        return $masterView;
    }

    /**
     * Get the view as a string.
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->render();
    }
}
