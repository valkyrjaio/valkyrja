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

namespace Valkyrja\View\Engines;

use Valkyrja\View\Exceptions\InvalidConfigPath;
use Valkyrja\View\PHPEngine as PHPEngineContract;
use Valkyrja\View\View;

use const ENT_QUOTES;
use const EXTR_OVERWRITE;

/**
 * Class PHPEngine.
 *
 * @author Melech Mizrachi
 */
class PHPEngine implements PHPEngineContract
{
    /**
     * The view.
     *
     * @var View
     */
    protected View $view;

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
     * PHPEngine constructor.
     *
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * Make a new engine.
     *
     * @param View $view The view
     *
     * @return static
     */
    public static function make(View $view): self
    {
        return new static($view);
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
    public function getVariable(string $key)
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
     * Set the layout for the view template.
     *
     * @param string $layout [optional] The layout to set
     *
     * @throws InvalidConfigPath
     *
     * @return static
     */
    public function setLayout(string $layout = null): self
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
        $this->layoutPath = $this->view->getFullPath($layout);

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
     * Output a partial.
     *
     * @param string $partial   The partial
     * @param array  $variables [optional]
     *
     * @throws InvalidConfigPath
     *
     * @return string
     */
    public function getPartial(string $partial, array $variables = []): string
    {
        return $this->render($this->view->getFullPath($partial), $variables);
    }

    /**
     * Output a block.
     *
     * @param string $name The block's name
     *
     * @return string
     */
    public function getBlock(string $name): string
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
    public function hasBlockEnded(string $name): bool
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
     * @return void
     */
    public function endBlock(string $name): void
    {
        unset($this->blockStatus[$name]);

        $this->blocks[$name] = ob_get_clean();
    }

    /**
     * Render a template.
     *
     * @param string $path      The path to render
     * @param array  $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(string $path, array $variables = []): string
    {
        // Set the variables with the new variables and this view instance
        $this->variables = array_merge($this->variables, $variables, ['view' => $this]);

        // Render the template
        $template = $this->renderTemplate($path);

        // Check if a layout has been set
        if (null === $this->layout || null === $this->layoutPath) {
            return $template;
        }

        // Begin tracking layout changes for recursive layout
        $this->trackLayoutChanges = true;

        return $this->renderLayout($this->layoutPath);
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
        if ($this->hasLayoutChanged && null !== $this->layoutPath) {
            // Reset the flag
            $this->hasLayoutChanged = false;
            // Render the new layout
            $renderedLayout = $this->renderLayout($this->layoutPath);
        }

        return $renderedLayout;
    }

    /**
     * Render a template.
     *
     * @param string $path      The path to render
     * @param array  $variables [optional] The variables to set
     *
     * @return string
     */
    protected function renderTemplate(string $path, array $variables = []): string
    {
        $variables = array_merge($this->variables, $variables);

        extract($variables, EXTR_OVERWRITE);

        ob_start();
        include $path;

        return ob_get_clean();
    }
}
