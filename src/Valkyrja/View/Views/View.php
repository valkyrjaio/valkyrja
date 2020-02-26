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

namespace Valkyrja\View\Views;

use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\View\Engine;
use Valkyrja\View\Exceptions\InvalidConfigPath;
use Valkyrja\View\View as ViewContract;

use const ENT_QUOTES;

/**
 * Class View.
 *
 * @author Melech Mizrachi
 */
class View implements ViewContract
{
    use Provides;

    /**
     * The engines.
     *
     * @var Engine[]
     */
    protected static array $engines = [];

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The layout template.
     *
     * @var string|null
     */
    protected ?string $layout = null;

    /**
     * The fully qualified layout path.
     *
     * @var string|null
     */
    protected ?string $layoutPath = null;

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
     * Whether to track layout changes.
     *
     * @var bool
     */
    protected bool $trackLayoutChanges = false;

    /**
     * Whether a layout change has occurred.
     *
     * @var bool
     */
    protected bool $hasLayoutChanged = false;

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
     * @param Application $app       The application
     * @param string|null $template  [optional] The template to set
     * @param array       $variables [optional] The variables to set
     *
     * @throws InvalidConfigPath
     */
    public function __construct(Application $app, string $template = null, array $variables = [])
    {
        $this->app    = $app;
        $this->config = $app->config()[ConfigKeyPart::VIEW];
        $this->engine = $this->config[ConfigKeyPart::ENGINE];
        $this->setVariables($variables);
        $this->setTemplateDir($this->app->config(ConfigKey::VIEW_DIR));
        $this->template($template ?? $this->template);
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ViewContract::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Application $app The application
     *
     * @throws InvalidConfigPath
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(ViewContract::class, new static($app));
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
        return new static($this->app, $template, $variables);
    }

    /**
     * Get a render engine.
     *
     * @param string|null $name The name of the engine
     *
     * @return Engine
     */
    public function engine(string $name = null): Engine
    {
        $name ??= $this->engine;

        if (isset(self::$engines[$name])) {
            return self::$engines[$name];
        }

        /** @var Engine $engine */
        $engine = $this->config[ConfigKeyPart::ENGINES][$name];

        return self::$engines[$name] = $engine::make($this);
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
     * Escape a value for output.
     *
     * @param string $value The value to escape
     *
     * @return string
     */
    public function escape(string $value): string
    {
        $value = mb_convert_encoding($value, 'UTF-8', 'UTF-8');
        $value = htmlentities($value, ENT_QUOTES, 'UTF-8');

        return $value;
    }

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    public function getTemplateDir(string $path = null): string
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
    public function setTemplateDir(string $path): self
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
     * Get the layout template path.
     *
     * @return string
     */
    public function getLayoutPath(): string
    {
        return $this->layoutPath;
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
            $path      = config('views.paths.' . $parts[0]);

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
            $path = $this->getTemplateDir($template);
        }

        return $path . $this->getFileExtension();
    }

    /**
     * Set the layout for the view template.
     *
     * @param string $layout [optional] The layout to set
     *
     * @throws InvalidConfigPath
     *
     * @return static
     */
    public function layout(string $layout = null): self
    {
        // If no layout has been set
        if (null === $layout) {
            // Set to null
            return $this->withoutLayout();
        }

        // If we should be tracking layout changes
        if ($this->trackLayoutChanges) {
            // Set the flag
            $this->hasLayoutChanged = true;
        }

        $this->layout     = $layout;
        $this->layoutPath = $this->getFullPath($layout);

        return $this;
    }

    /**
     * Set no layout for this view.
     *
     * @return static
     */
    public function withoutLayout(): self
    {
        $this->layout     = null;
        $this->layoutPath = null;

        return $this;
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
    public function template(string $template): self
    {
        $this->template     = $template;
        $this->templatePath = $this->getFullPath($template);

        return $this;
    }

    /**
     * Output a partial.
     *
     * @param string $partial   The partial
     * @param array  $variables [optional] The variables
     *
     * @return string
     */
    public function partial(string $partial, array $variables = []): string
    {
        return $this->engine()->partial($partial, $variables);
    }

    /**
     * Output a block.
     *
     * @param string $name The name of the block
     *
     * @return string
     */
    public function block(string $name): string
    {
        return $this->engine()->block($name);
    }

    /**
     * Determine if a block exists.
     *
     * @param string $name The name of the block
     *
     * @return bool
     *  True if the block exists
     *  False if the block doesn't exist
     */
    public function hasBlock(string $name): bool
    {
        return $this->engine()->hasBlock($name);
    }

    /**
     * Determine if a block has been ended.
     *
     * @param string $name The name of the block
     *
     * @return bool
     *  True if the block has been ended
     *  False if the block has not yet been ended
     */
    public function hasBlockBeenEnded(string $name): bool
    {
        return $this->engine()->hasBlockBeenEnded($name);
    }

    /**
     * Start a block.
     *
     * @param string $name The name of the block
     *
     * @return void
     */
    public function startBlock(string $name): void
    {
        $this->engine()->startBlock($name);
    }

    /**
     * End a block.
     *
     * @param string $name The name of the block
     *
     * @return void
     */
    public function endBlock(string $name): void
    {
        $this->engine()->endBlock($name);
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
        $this->variables = array_merge($this->variables, $variables, ['view' => $this]);

        // Render the template
        $template = $this->engine()->render($this->templatePath);

        // Check if a layout has been set
        if (null === $this->layout) {
            return $template;
        }

        // Begin tracking layout changes for recursive layout
        $this->trackLayoutChanges = true;

        return $this->renderLayout($this->layoutPath);
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

    /**
     * Render a layout path.
     *
     * @param string $layoutPath The layout path
     *
     * @return string
     */
    protected function renderLayout(string $layoutPath): string
    {
        // Render the layout
        $renderedLayout = $this->engine()->render($layoutPath);

        // Check if the layout has changed
        if ($this->hasLayoutChanged) {
            // Reset the flag
            $this->hasLayoutChanged = false;
            // Render the new layout
            $renderedLayout = $this->renderLayout($this->layoutPath);
        }

        return $renderedLayout;
    }
}
