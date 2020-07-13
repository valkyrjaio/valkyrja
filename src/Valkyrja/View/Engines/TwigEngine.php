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

namespace Valkyrja\View\Engines;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Valkyrja\View\Engine;

/**
 * Class TwigEngine.
 *
 * @author Melech Mizrachi
 */
class TwigEngine implements Engine
{
    /**
     * The twig environment.
     *
     * @var Environment
     */
    protected Environment $twig;

    /**
     * TwigEngine constructor.
     *
     * @param Environment $twig The Twig environment
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Start rendering.
     *
     * @return void
     */
    public function startRender(): void
    {
    }

    /**
     * End rendering.
     *
     * @return string
     */
    public function endRender(): string
    {
        return '';
    }

    /**
     * Render a file.
     *
     * @param string $name      The file name
     * @param array  $variables [optional] The variables
     *
     * @throws LoaderError  When the template cannot be found
     * @throws SyntaxError  When an error occurred during compilation
     * @throws RuntimeError When an error occurred during rendering
     *
     * @return string
     */
    public function renderFile(string $name, array $variables = []): string
    {
        return $this->twig->render($name, $variables);
    }
}
