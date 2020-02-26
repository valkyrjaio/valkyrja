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

use Valkyrja\View\Engine;
use Valkyrja\View\Exceptions\InvalidConfigPath;
use Valkyrja\View\View;

use const EXTR_OVERWRITE;

/**
 * Class PHPEngine.
 *
 * @author Melech Mizrachi
 */
class PHPEngine implements Engine
{
    /**
     * The view.
     *
     * @var View
     */
    protected View $view;

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
        $variables = array_merge($this->view->getVariables(), $variables);

        extract($variables, EXTR_OVERWRITE);

        ob_start();
        include $path;

        return ob_get_clean();
    }
}
