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

use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Support\Directory;
use Valkyrja\Support\Providers\Provides;
use Valkyrja\View\Exceptions\InvalidConfigPath;

use const ENT_QUOTES;
use const EXTR_OVERWRITE;

/**
 * Class View.
 *
 * @author Melech Mizrachi
 */
class PhpView implements View
{
    use Provides;

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
     * The block status.
     *
     * @var array
     */
    protected array $blockStatus = [];

    /**
     * The view blocks.
     *
     * @var array
     */
    protected array $blocks = [];

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
     * View constructor.
     *
     * @param Application $app       The application
     * @param string      $template  [optional] The template to set
     * @param array       $variables [optional] The variables to set
     *
     * @throws InvalidConfigPath
     */
    public function __construct(Application $app, string $template = null, array $variables = [])
    {
        $this->app = $app;
        $this->setVariables($variables);
        $this->setTemplateDir($this->app->config(ConfigKey::VIEWS_DIR));
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
            View::class,
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
        $app->container()->singleton(View::class, new static($app));
    }

    /**
     * Make a new View.
     *
     * @param string $template  [optional] The template to set
     * @param array  $variables [optional] The variables to set
     *
     * @throws InvalidConfigPath
     *
     * @return View
     */
    public function make(string $template = null, array $variables = []): View
    {
        return new static($this->app, $template, $variables);
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
     * @return View
     */
    public function setVariables(array $variables = []): View
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
     * @return View
     */
    public function setVariable(string $key, $value): View
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
     * @param string $templateDir The path to set
     *
     * @return View
     */
    public function setTemplateDir(string $templateDir): View
    {
        $this->templateDir = $templateDir;

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
     * @return View
     */
    public function setFileExtension(string $extension): View
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
     * Set the layout for the view template.
     *
     * @param string $layout [optional] The layout to set
     *
     * @throws InvalidConfigPath
     *
     * @return View
     */
    public function layout(string $layout = null): View
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
     * @return View
     */
    public function withoutLayout(): View
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
     * @return View
     */
    public function template(string $template): View
    {
        $this->template     = $template;
        $this->templatePath = $this->getFullPath($template);

        return $this;
    }

    /**
     * Output a partial.
     *
     * @param string $partial   The partial
     * @param array  $variables [optional]
     *
     * @throws InvalidConfigPath
     *
     * @return string
     */
    public function partial(string $partial, array $variables = []): string
    {
        return $this->renderTemplate($this->getFullPath($partial), $variables);
    }

    /**
     * Output a block.
     *
     * @param string $name The block's name
     *
     * @return string
     */
    public function block(string $name): string
    {
        return $this->blocks[$name] ?? '';
    }

    /**
     * Determine if a block exists.
     *
     * @param string $name
     *
     * @return bool
     *  True if the block exists
     *  False if the block doesn't exist
     */
    public function hasBlock(string $name): bool
    {
        return isset($this->blocks[$name]);
    }

    /**
     * Determine if a block has been ended.
     *
     * @param string $name
     *
     * @return bool
     *  True if the block has been ended
     *  False if the block has not yet been ended
     */
    public function hasBlockBeenEnded(string $name): bool
    {
        return ! isset($this->blockStatus[$name]);
    }

    /**
     * Start a block.
     *
     * @param string $name The block's name
     *
     * @return void
     */
    public function startBlock(string $name): void
    {
        $this->blockStatus[$name] = $name;

        ob_start();
    }

    /**
     * End a block.
     *
     * @param string $name The block's name
     *
     * @return string
     */
    public function endBlock(string $name): string
    {
        unset($this->blockStatus[$name]);

        return $this->blocks[$name] = ob_get_clean();
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
        $template = $this->renderTemplate($this->templatePath);

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
     * Get the full path for a given template.
     *
     * @param string $template The template
     *
     * @throws InvalidConfigPath
     *
     * @return string
     */
    protected function getFullPath(string $template): string
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
     * Render a template.
     *
     * @param string $templatePath The template path
     * @param array  $variables    [optional] The variables to set
     *
     * @return string
     */
    protected function renderTemplate(string $templatePath, array $variables = []): string
    {
        $variables = array_merge($this->variables, $variables);

        extract($variables, EXTR_OVERWRITE);

        ob_start();
        include $templatePath;

        return ob_get_clean();
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
        $renderedLayout = $this->renderTemplate($layoutPath);

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
