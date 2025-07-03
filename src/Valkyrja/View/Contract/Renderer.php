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

use Valkyrja\View\Template\Contract\Template;

/**
 * Interface Renderer.
 *
 * @author Melech Mizrachi
 */
interface Renderer
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
     * Render a template.
     *
     * @param non-empty-string     $name      The template name
     * @param array<string, mixed> $variables [optional] The variables
     */
    public function render(string $name, array $variables = []): string;

    /**
     * Create a new template.
     *
     * @param non-empty-string     $name      The template name
     * @param array<string, mixed> $variables [optional] The variables
     */
    public function createTemplate(string $name, array $variables = []): Template;

    /**
     * Render a template file.
     *
     * @param string               $name      The file name
     * @param array<string, mixed> $variables [optional] The variables
     *
     * @return string
     */
    public function renderFile(string $name, array $variables = []): string;
}
