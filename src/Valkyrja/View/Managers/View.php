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

namespace Valkyrja\View\Managers;

use Valkyrja\Container\Container;
use Valkyrja\Support\Directory;
use Valkyrja\View\Engine;
use Valkyrja\View\Exceptions\InvalidConfigPath;
use Valkyrja\View\View as Contract;

use function array_merge;
use function explode;
use function implode;
use function strpos;
use function trim;

/**
 * Class View.
 *
 * @author Melech Mizrachi
 */
class View implements Contract
{
    /**
     * The engines.
     *
     * @var Engine[]
     */
    protected static array $engines = [];

    /**
     * The container.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The body content template.
     *
     * @var string
     */
    protected string $template = 'index';

    /**
     * The fully qualified template path.
     *
     * @var string
     */
    protected string $templatePath;

    /**
     * The template directory.
     *
     * @var string
     */
    protected string $templateDir;

    /**
     * @var string
     */
    protected string $fileExtension = '.phtml';

    /**
     * The view variables.
     *
     * @var array
     */
    protected array $variables = [];

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * The default engine.
     *
     * @var string
     */
    protected string $engine;

    /**
     * View constructor.
     *
     * @param Container   $container The container
     * @param array       $config    The config
     * @param string|null $template  [optional] The template to set
     * @param array       $variables [optional] The variables to set
     */
    public function __construct(Container $container, array $config, string $template = null, array $variables = [])
    {
        $this->container = $container;
        $this->config    = $config;
        $this->engine    = $config['engine'];

        $this->setVariables($variables);
        $this->setDir($this->config['dir']);
        $this->setTemplate($template ?? $this->template);
    }

    /**
     * Make a new View.
     *
     * @param string|null $template  [optional] The template to set
     * @param array       $variables [optional] The variables to set
     *
     * @throws InvalidConfigPath
     *
     * @return static
     */
    public function make(string $template = null, array $variables = []): self
    {
        return new static($this->container, $this->config, $template, $variables);
    }

    /**
     * Get a render engine.
     *
     * @param string|null $name The name of the engine
     *
     * @return Engine
     */
    public function getEngine(string $name = null): Engine
    {
        $name ??= $this->config['engine'];

        return self::$engines[$name]
            ?? self::$engines[$name] = $this->container->getSingleton(
                $this->config['engines'][$name]
            );
    }

    /**
     * Get the variables.
     *
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Set the variables.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return static
     */
    public function setVariables(array $variables = []): self
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * Get a variable.
     *
     * @param string $key The variable key to set
     *
     * @return mixed
     */
    public function variable(string $key)
    {
        return $this->variables[$key] ?? null;
    }

    /**
     * Set a single variable.
     *
     * @param string $key   The variable key to set
     * @param mixed  $value The value to set
     *
     * @return static
     */
    public function setVariable(string $key, $value): self
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getDir(string $path = null): string
    {
        return $this->templateDir . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
                : $path);
    }

    /**
     * Set the template directory.
     *
     * @param string $path The path to set
     *
     * @return static
     */
    public function setDir(string $path): self
    {
        $this->templateDir = $path;

        return $this;
    }

    /**
     * Get the file extension.
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        return $this->fileExtension;
    }

    /**
     * Set the file extension.
     *
     * @param string $extension The extension to set
     *
     * @return static
     */
    public function setFileExtension(string $extension): self
    {
        $this->fileExtension = $extension;

        return $this;
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * Set the template for the view.
     *
     * @param string $template The template
     *
     * @throws InvalidConfigPath
     *
     * @return static
     */
    public function setTemplate(string $template): self
    {
        $this->template     = $template;
        $this->templatePath = $this->getFullPath($template);

        return $this;
    }

    /**
     * Get the full path for a given template.
     *
     * @param string $template The template
     *
     * @throws InvalidConfigPath
     *
     * @return string
     */
    public function getFullPath(string $template): string
    {
        // If the first character of the template is an @ symbol
        // Then this is a template from a path in the config
        if (strpos($template, '@') === 0) {
            $explodeOn = Directory::DIRECTORY_SEPARATOR;
            $parts     = explode($explodeOn, $template);
            $path      = $this->config['paths'][$parts[0]] ?? null;

            // If there is no path
            if ($path === null) {
                // Then throw an exception
                throw new InvalidConfigPath(
                    'Invalid path '
                    . $parts[0]
                    . ' specified for template '
                    . $template
                );
            }

            // Remove any trailing slashes
            $parts[0] = $explodeOn . trim($path, $explodeOn);

            $path = implode($explodeOn, $parts);
        } else {
            $path = $this->getDir($template);
        }

        return $path . $this->getFileExtension();
    }

    /**
     * Render the templates and view.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(array $variables = []): string
    {
        // Set the variables with the new variables and this view instance
        $this->variables = array_merge($this->variables, $variables);

        // Render the template
        return $this->getEngine()->render($this->templatePath, $this->variables);
    }

    /**
     * Get the view as a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->render();
    }
}
