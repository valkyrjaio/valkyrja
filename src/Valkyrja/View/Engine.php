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

/**
 * Interface Engine.
 *
 * @author Melech Mizrachi
 */
interface Engine
{
    /**
     * Render a template.
     *
     * @param string $path      The path to render
     * @param array  $variables [optional] The variables to set
     *
     * @return string
     */
    public function render(string $path, array $variables = []): string;
}
