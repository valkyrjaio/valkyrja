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

namespace Valkyrja\Twig;

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
     * View constructor.
     *
     * @param Environment $twig The Twig environment
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render a template.
     *
     * @param string $path      The path to render
     * @param array  $variables [optional] The variables to set
     *
     * @throws LoaderError  When the template cannot be found
     * @throws SyntaxError  When an error occurred during compilation
     * @throws RuntimeError When an error occurred during rendering
     *
     * @return string
     */
    public function render(string $path, array $variables = []): string
    {
        return $this->twig->render($path, $variables);
    }
}
