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

namespace Valkyrja\View\Engine\Contract;

/**
 * Interface Engine.
 *
 * @author Melech Mizrachi
 */
interface Engine
{
    /**
     * Start rendering.
     *
     * @return void
     */
    public function startRender(): void;

    /**
     * End rendering.
     *
     * @return string
     */
    public function endRender(): string;

    /**
     * Render a file.
     *
     * @param string               $name      The file name
     * @param array<string, mixed> $variables [optional] The variables
     *
     * @return string
     */
    public function renderFile(string $name, array $variables = []): string;
}
