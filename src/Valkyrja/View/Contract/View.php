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

namespace Valkyrja\View\Contract;

use Valkyrja\View\Engine\Contract\Engine;
use Valkyrja\View\Template\Contract\Template;

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
     * @param string               $name      The template to set
     * @param array<string, mixed> $variables [optional] The variables to set
     * @param string|null          $engine    [optional] The engine to use
     *
     * @return Template
     */
    public function createTemplate(string $name, array $variables = [], string|null $engine = null): Template;

    /**
     * Get a render engine.
     *
     * @param string|null $name The name of the engine
     *
     * @return Engine
     */
    public function getEngine(string|null $name = null): Engine;

    /**
     * Render a template.
     *
     * @param string               $name      The name of the template to render
     * @param array<string, mixed> $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(string $name, array $variables = []): string;
}
