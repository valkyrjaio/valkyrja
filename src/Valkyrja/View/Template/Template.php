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

namespace Valkyrja\View\Template;

use Override;
use Valkyrja\Exception\InvalidArgumentException;
use Valkyrja\View\Contract\Renderer;
use Valkyrja\View\Template\Contract\Template as Contract;

use function array_merge;
use function htmlentities;
use function is_string;

use const ENT_QUOTES;

/**
 * Class Template.
 *
 * @author Melech Mizrachi
 */
class Template implements Contract
{
    /**
     * The layout template.
     *
     * @var string|null
     */
    protected string|null $layout = null;

    /**
     * The block status.
     *
     * @var string[]
     */
    protected array $blockStatus = [];

    /**
     * The view blocks.
     *
     * @var string[]
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
     * Template constructor.
     *
     * @param array<string, mixed> $variables The variables
     */
    public function __construct(
        protected Renderer $renderer,
        protected string $name,
        protected array $variables = []
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setVariables(array $variables = []): static
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getVariable(string $key): mixed
    {
        return $this->variables[$key] ?? null;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setVariable(string $key, $value): static
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function escape(string|int|float $value): string
    {
        /** @var string|false $encodedValue */
        $encodedValue = mb_convert_encoding((string) $value, 'UTF-8', 'UTF-8');

        if (! is_string($encodedValue)) {
            throw new InvalidArgumentException("Error occurred when encoding `$value`");
        }

        return htmlentities($encodedValue, ENT_QUOTES, 'UTF-8');
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function setLayout(string|null $layout = null): static
    {
        // If no layout has been set
        if ($layout === null) {
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
     * @inheritDoc
     */
    #[Override]
    public function withoutLayout(): static
    {
        $this->layout = null;

        return $this;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPartial(string $partial, array $variables = []): string
    {
        return $this->renderFile($partial, $variables);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getBlock(string $name): string
    {
        return $this->blocks[$name] ?? '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasBlock(string $name): bool
    {
        return isset($this->blocks[$name]);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function startBlock(string $name): void
    {
        $this->blockStatus[] = $name;

        $this->renderer->startRender();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function endBlock(): void
    {
        // Get the last item in the array (newest block to close)
        $block = end($this->blockStatus);
        // Remove the last item in the array (as we are now closing it out)
        array_pop($this->blockStatus);
        // Render the block and set the value in the blocks array
        $this->blocks[$block] = $this->renderer->endRender();
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function render(array $variables = []): string
    {
        return $this->renderFile($this->name, $variables, true);
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->render();
    }

    /**
     * Render a file.
     *
     * @param string               $name         The file name
     * @param array<string, mixed> $variables    [optional] The variables to set
     * @param bool                 $renderLayout [optional] Whether to render the layout
     *
     * @return string
     */
    protected function renderFile(string $name, array $variables = [], bool $renderLayout = false): string
    {
        // Set the variables with the new variables and this view instance
        $variables = array_merge($this->variables, $variables, ['template' => $this]);

        // Render the template
        $template = $this->renderTemplate($name, $variables);

        // Check if a layout has been set
        if ($this->layout === null || ! $renderLayout) {
            return $template;
        }

        // Begin tracking layout changes for recursive layout
        $this->trackLayoutChanges = true;

        return $this->renderLayout($this->layout, $variables);
    }

    /**
     * Render a layout.
     *
     * @param string               $layout    The layout
     * @param array<string, mixed> $variables [optional] The variables to set
     *
     * @return string
     */
    protected function renderLayout(string $layout, array $variables = []): string
    {
        // Render the layout
        $renderedLayout = $this->renderTemplate($layout, $variables);

        // Check if the layout has changed
        if ($this->trackLayoutChanges && $this->hasLayoutChanged && $this->layout !== null) {
            // Reset the flag
            $this->hasLayoutChanged = false;
            // Render the new layout
            $renderedLayout = $this->renderLayout($this->layout, $variables);
        }

        return $renderedLayout;
    }

    /**
     * Render a template.
     *
     * @param string               $path      The path to render
     * @param array<string, mixed> $variables [optional] The variables to set
     *
     * @return string
     */
    protected function renderTemplate(string $path, array $variables = []): string
    {
        $variables = array_merge($this->variables, $variables);

        return $this->renderer->renderFile($path, $variables);
    }
}
