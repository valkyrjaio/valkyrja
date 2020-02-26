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

use Valkyrja\View\Exceptions\InvalidConfigPath;

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
     * @param array       $variables [optional] The variables to set
     *
     * @return static
     */
    public function make(string $template = null, array $variables = []): self;

    /**
     * Get a render engine.
     *
     * @param string|null $name The name of the engine
     *
     * @return Engine
     */
    public function getEngine(string $name = null): Engine;

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
     * @return static
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
     * @return static
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
     * @param string $path The path to set
     *
     * @return static
     */
    public function setTemplateDir(string $path): self;

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
     * @return static
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
     * Get the full path for a given template.
     *
     * @param string $template The template
     *
     * @throws InvalidConfigPath
     *
     * @return string
     */
    public function getFullPath(string $template): string;

    /**
     * Set the layout for the view template.
     *
     * @param string $layout [optional]
     *
     * @return static
     */
    public function setLayout(string $layout = null): self;

    /**
     * Set no layout for this view.
     *
     * @return static
     */
    public function withoutLayout(): self;

    /**
     * Set the template for the view.
     *
     * @param string $template The template
     *
     * @return static
     */
    public function setTemplate(string $template): self;

    /**
     * Output a partial.
     *
     * @param string $partial   The partial
     * @param array  $variables [optional] The variables
     *
     * @return string
     */
    public function getPartial(string $partial, array $variables = []): string;

    /**
     * Output a block.
     *
     * @param string $name The name of the block
     *
     * @return string
     */
    public function getBlock(string $name): string;

    /**
     * Determine if a block exists.
     *
     * @param string $name The name of the block
     *
     * @return bool
     *  True if the block exists
     *  False if the block doesn't exist
     */
    public function hasBlock(string $name): bool;

    /**
     * Determine if a block has been ended.
     *
     * @param string $name The name of the block
     *
     * @return bool
     *  True if the block has been ended
     *  False if the block has not yet been ended
     */
    public function hasBlockEnded(string $name): bool;

    /**
     * Start a block.
     *
     * @param string $name The name of the block
     *
     * @return void
     */
    public function startBlock(string $name): void;

    /**
     * End a block.
     *
     * @param string $name The name of the block
     *
     * @return void
     */
    public function endBlock(string $name): void;

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
