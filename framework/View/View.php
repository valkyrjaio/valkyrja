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
    public function __construct($template = '', array $variables = [])
    {
        $this->setVariables($variables);
        $this->setTemplate($template);
        $this->setTemplateDir(config('views.dir', resourcesPath('views')));
    }

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return View
     */
    public function make($template = '', array $variables = []) : View
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
    public function setMasterTemplate($template) : void
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
    public function setTemplate($template) : void
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
    public function setVariables(array $variables = []) : void
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
    public function variable($key, $value) : void
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
    public function getTemplateDir($path = null) : string
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
    public function setTemplateDir($templateDir) : void
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
    public function setFileExtension($extension) : void
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

        if (!$this->masterTemplate || $this->masterTemplate === '') {
            return $view;
        }

        extract(['body' => $view]);

        ob_start();
        include $this->getMasterTemplatePath();
        $masterView = ob_get_clean();

        return $masterView;
    }
}
