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
     * @inheritdoc
     */
    public function __construct($template = '', array $variables = [])
    {
        $this->setVariables($variables);
        $this->setTemplate($template);
        $this->setTemplateDir(config('views.dir', resourcesPath('views')));
    }

    /**
     * @inheritdoc
     */
    public function make($template = '', array $variables = [])
    {
        return new static($template, $variables);
    }

    /**
     * @inheritdoc
     */
    public function setMasterTemplate($template)
    {
        $this->masterTemplate = $template;
    }

    /**
     * @inheritdoc
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @inheritdoc
     */
    public function setVariables(array $variables = [])
    {
        $this->variables = array_merge($this->variables, $variables);
    }

    /**
     * @inheritdoc
     */
    public function variable($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getTemplateDir($path = null)
    {
        return $this->templateDir . ($path
            ? app()::DIRECTORY_SEPARATOR . $path
            : $path);
    }

    /**
     * @inheritdoc
     */
    public function setTemplateDir($templateDir)
    {
        $this->templateDir = $templateDir;
    }

    /**
     * @inheritdoc
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @inheritdoc
     */
    public function setFileExtension($extension)
    {
        $this->fileExtension = $extension;
    }

    /**
     * @inheritdoc
     */
    public function getTemplatePath()
    {
        return $this->getTemplateDir($this->template . $this->getFileExtension());
    }

    /**
     * @inheritdoc
     */
    public function getMasterTemplatePath()
    {
        return $this->getTemplateDir($this->masterTemplate . $this->getFileExtension());
    }

    /**
     * @inheritdoc
     */
    public function render(array $variables = [])
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
