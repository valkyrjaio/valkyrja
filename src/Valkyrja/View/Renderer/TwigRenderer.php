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

namespace Valkyrja\View\Renderer;

use Override;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Valkyrja\View\Renderer\Contract\RendererContract;
use Valkyrja\View\Template\Contract\TemplateContract;
use Valkyrja\View\Template\Template;

class TwigRenderer implements RendererContract
{
    public function __construct(
        protected Environment $twig
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function startRender(): void
    {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function endRender(): string
    {
        return '';
    }

    /**
     * @inheritDoc
     *
     * @throws LoaderError  When the template cannot be found
     * @throws SyntaxError  When an error occurred during compilation
     * @throws RuntimeError When an error occurred during rendering
     */
    #[Override]
    public function render(string $name, array $variables = []): string
    {
        return $this->renderFile($name, $variables);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function createTemplate(string $name, array $variables = []): TemplateContract
    {
        return new Template(
            renderer: $this,
            name: $name,
            variables: $variables
        );
    }

    /**
     * @inheritDoc
     *
     * @throws LoaderError  When the template cannot be found
     * @throws SyntaxError  When an error occurred during compilation
     * @throws RuntimeError When an error occurred during rendering
     */
    #[Override]
    public function renderFile(string $name, array $variables = []): string
    {
        return $this->twig->render($name, $variables);
    }
}
