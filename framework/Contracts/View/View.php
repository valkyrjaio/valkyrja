<?php
/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Valkyrja\Contracts\View;

/**
 * Interface View
 *
 * @package Valkyrja\Contracts\View
 *
 * @author  Melech Mizrachi
 */
interface View
{
    /**
     * View constructor.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     */
    public function __construct($template = '', array $variables = []);

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return View
     */
    public function make($template = '', array $variables = []);

    /**
     * Set the master template.
     *
     * @param string $template The master template to set
     *
     * @return void
     */
    public function setMasterTemplate($template);

    /**
     * Set the template.
     *
     * @param string $template The template to set
     *
     * @return void
     */
    public function setTemplate($template);

    /**
     * Set the variables
     *
     * @param array $variables [optional] The variables to set
     *
     * @return void
     */
    public function setVariables(array $variables = []);

    /**
     * Set a single variable.
     *
     * @param string $key   The variable key to set
     * @param mixed  $value The value to set
     *
     * @return void
     */
    public function variable($key, $value);

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getTemplateDir($path = null);

    /**
     * Set the template directory.
     *
     * @param string $templateDir The path to set
     *
     * @return void
     */
    public function setTemplateDir($templateDir);

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getFileExtension();

    /**
     * Set the file extension.
     *
     * @param string $extension The extension to set
     */
    public function setFileExtension($extension);

    /**
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath();

    /**
     * Get the master template path.
     *
     * @return string
     */
    public function getMasterTemplatePath();

    /**
     * Render the templates and view.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(array $variables = []);
}
