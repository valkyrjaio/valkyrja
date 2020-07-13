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

namespace Valkyrja\View\Templates;

use Valkyrja\View\Engine;
use Valkyrja\View\Template as Contract;
use Valkyrja\View\View;

use function array_merge;
use function htmlentities;

use const ENT_QUOTES;

/**
 * Class Template.
 *
 * @author Melech Mizrachi
 */
class Template implements Contract
{
    /**
     * The engine.
     *
     * @var Engine
     */
    protected Engine $engine;

    /**
     * The view.
     *
     * @var View
     */
    protected View $view;

    /**
     * The template name.
     *
     * @var string|null
     */
    protected ?string $name = null;

    /**
     * The layout template.
     *
     * @var string|null
     */
    protected ?string $layout = null;

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
     * Template constructor.
     *
     * @param Engine $engine The engine
     */
    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Create a new template.
     *
     * @param Engine $engine The engine
     *
     * @return static
     */
    public static function createTemplate(Engine $engine): self
    {
        return new static($engine);
    }

    /**
     * Get the template name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the template name.
     *
     * @param string $name The name
     *
     * @return static
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
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

        $this->layout = $layout;

        return $this;
    }

    /**
     * Set no layout for this view.
     *
     * @return static
     */
    public function withoutLayout(): self
    {
        $this->layout = null;

        return $this;
    }

    /**
     * Get a partial.
     *
     * @param string $partial   The partial
     * @param array  $variables [optional]
     *
     * @return string
     */
    public function getPartial(string $partial, array $variables = []): string
    {
        return $this->renderFile($partial, $variables);
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

        $this->engine->startRender();
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

        $this->blocks[$name] = $this->engine->endRender();
    }

    /**
     * Render the template.
     *
     * @param array $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(array $variables = []): string
    {
        return $this->renderFile($this->name, $variables);
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
     * Render a file.
     *
     * @param string $name      The file name
     * @param array  $variables [optional] The variables to set
     *
     * @return string
     */
    protected function renderFile(string $name, array $variables = []): string
    {
        // Set the variables with the new variables and this view instance
        $this->variables = array_merge($this->variables, $variables, ['template' => $this]);

        // Render the template
        $template = $this->renderTemplate($name);

        // Check if a layout has been set
        if (null === $this->layout) {
            return $template;
        }

        // Begin tracking layout changes for recursive layout
        $this->trackLayoutChanges = true;

        return $this->renderLayout($this->layout);
    }

    /**
     * Render a layout.
     *
     * @param string $layout The layout
     *
     * @return string
     */
    protected function renderLayout(string $layout): string
    {
        // Render the layout
        $renderedLayout = $this->renderTemplate($layout);

        // Check if the layout has changed
        if ($this->trackLayoutChanges && $this->hasLayoutChanged && null !== $this->layout) {
            // Reset the flag
            $this->hasLayoutChanged = false;
            // Render the new layout
            $renderedLayout = $this->renderLayout($this->layout);
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

        return $this->engine->renderFile($path, $variables);
    }
}
