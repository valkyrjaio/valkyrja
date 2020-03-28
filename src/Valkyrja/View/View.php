<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
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
     * Get the directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getDir(string $path = null): string;

    /**
     * Set the directory.
     *
     * @param string $path The path to set
     *
     * @return static
     */
    public function setDir(string $path): self;

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
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath(): string;

    /**
     * Set the template for the view.
     *
     * @param string $template The template
     *
     * @return static
     */
    public function setTemplate(string $template): self;

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
