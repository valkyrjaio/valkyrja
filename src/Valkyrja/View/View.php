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

/**
 * Interface View.
 *
 * @author Melech Mizrachi
 */
interface View
{
    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @return View
     */
    public function make(string $template = null, array $variables = []): self;

    /**
     * Get the variables.
     *
     * @return array
     */
    public function getVariables(): array;

    /**
     * Set the variables.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return \Valkyrja\View\View
     */
    public function setVariables(array $variables = []): self;

    /**
     * Get a variable.
     *
     * @param string $key The variable key to set
     *
     * @return mixed
     */
    public function variable(string $key);

    /**
     * Set a single variable.
     *
     * @param string $key   The variable key to set
     * @param mixed  $value The value to set
     *
     * @return \Valkyrja\View\View
     */
    public function setVariable(string $key, $value): self;

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
     * @return \Valkyrja\View\View
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
     * @return \Valkyrja\View\View
     */
    public function setFileExtension(string $extension): self;

    /**
     * Get the layout template path.
     *
     * @return string
     */
    public function getLayoutPath(): string;

    /**
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * Set the layout for the view template.
     *
     * @param string $layout [optional]
     *
     * @return \Valkyrja\View\View
     */
    public function layout(string $layout = null): self;

    /**
     * Set no layout for this view.
     *
     * @return \Valkyrja\View\View
     */
    public function withoutLayout(): self;

    /**
     * Set the template for the view.
     *
     * @param string $template The template
     *
     * @return \Valkyrja\View\View
     */
    public function template(string $template): self;

    /**
     * Output a partial.
     *
     * @param string $partial
     * @param array  $variables [optional]
     *
     * @return string
     */
    public function partial(string $partial, array $variables = []): string;

    /**
     * Output a block.
     *
     * @param string $name
     *
     * @return string
     */
    public function block(string $name): string;

    /**
     * Start a block.
     *
     * @param string $name
     *
     * @return void
     */
    public function startBlock(string $name): void;

    /**
     * End a block.
     *
     * @param string $name
     *
     * @return string
     */
    public function endBlock(string $name): string;

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
