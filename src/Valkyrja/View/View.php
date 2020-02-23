<?php

declare(strict_types=1);

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
     * @param string|null $template  [optional] The template to set
     * @param array|null  $variables [optional] The variables to set
     *
     * @return View
     */
    public function make(string $template = null, array $variables = null): self;

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
     * @return View
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
     * @return View
     */
    public function setVariable(string $key, $value): self;

    /**
     * Escape a value for output.
     *
     * @param string $value The value to escape
     *
     * @return string
     */
    public function escape(string $value): string;

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
     * @return View
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
     * @return View
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
     * @return View
     */
    public function layout(string $layout = null): self;

    /**
     * Set no layout for this view.
     *
     * @return View
     */
    public function withoutLayout(): self;

    /**
     * Set the template for the view.
     *
     * @param string $template The template
     *
     * @return View
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
     * Determine if a block exists.
     *
     * @param string $name
     *
     * @return bool
     *  True if the block exists
     *  False if the block doesn't exist
     */
    public function hasBlock(string $name): bool;

    /**
     * Determine if a block has been ended.
     *
     * @param string $name
     *
     * @return bool
     *  True if the block has been ended
     *  False if the block has not yet been ended
     */
    public function hasBlockBeenEnded(string $name): bool;

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
