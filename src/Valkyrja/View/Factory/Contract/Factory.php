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

namespace Valkyrja\View\Factory\Contract;

use Valkyrja\View\Engine\Contract\Engine;
use Valkyrja\View\Template\Contract\Template;

/**
 * Interface Factory.
 *
 * @author Melech Mizrachi
 */
interface Factory
{
    /**
     * Get a template.
     *
     * @param Engine               $engine    The engine to use
     * @param string               $name      The template name
     * @param array<string, mixed> $variables [optional] The variables
     *
     * @return Template
     */
    public function getTemplate(Engine $engine, string $name, array $variables = []): Template;

    /**
     * Get an engine.
     *
     * @param class-string<Engine> $name The name
     *
     * @return Engine
     */
    public function getEngine(string $name): Engine;
}
