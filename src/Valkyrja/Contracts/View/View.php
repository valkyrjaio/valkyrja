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

use Valkyrja\Contracts\Application;

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
     * @param \Valkyrja\Contracts\Application $app       The application
     * @param string                          $template  [optional] The template to set
     * @param array                           $variables [optional] The variables to set
     */
    public function __construct(Application $app, string $template = '', array $variables = []);

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return View
     */
    public function make(string $template = '', array $variables = []): self;

    /**
     * Set the layout template.
     *
     * @param string $template The master template to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setLayout(string $template): self;

    /**
     * Set to use no layout.
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function withoutLayout(): self;

    /**
     * Set the template.
     *
     * @param string $template The template to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setTemplate(string $template): self;

    /**
     * Set the variables
     *
     * @param array $variables [optional] The variables to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setVariables(array $variables = []): self;

    /**
     * Set a single variable.
     *
     * @param string $key   The variable key to set
     * @param mixed  $value The value to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function variable(string $key, $value): self;

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getTemplateDir(string $path = null): string;

    /**
     * Set the template directory.
     *
     * @param string $templateDir The path to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setTemplateDir(string $templateDir): self;

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getFileExtension(): string;

    /**
     * Set the file extension.
     *
     * @param string $extension The extension to set
     *
     * @return \Valkyrja\Contracts\View\View
     */
    public function setFileExtension(string $extension): self;

    /**
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * Get the layout template path.
     *
     * @return string
     */
    public function getLayoutPath(): string;

    /**
     * Render the templates and view.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(array $variables = []): string;

    /**
     * Get the view as a string.
     *
     * @return string
     */
    public function __toString(): string;
}
