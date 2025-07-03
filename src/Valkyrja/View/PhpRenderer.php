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

use Valkyrja\Exception\RuntimeException;
use Valkyrja\View\Contract\Renderer as Contract;
use Valkyrja\View\Exception\InvalidConfigPath;
use Valkyrja\View\Template\Contract\Template;
use Valkyrja\View\Template\Template as DefaultTemplate;

use function explode;
use function extract;
use function implode;
use function ob_get_clean;
use function ob_start;
use function trim;

use const DIRECTORY_SEPARATOR;
use const EXTR_SKIP;

/**
 * Class PhpRenderer.
 *
 * @author Melech Mizrachi
 */
class PhpRenderer implements Contract
{
    /**
     * PhpRenderer constructor.
     *
     * @param array<string, string> $paths
     */
    public function __construct(
        protected string $dir,
        protected string $fileExtension = '.phtml',
        protected array $paths = [],
    ) {
    }

    /**
     * @inheritDoc
     */
    public function startRender(): void
    {
        ob_start();
    }

    /**
     * @inheritDoc
     */
    public function endRender(): string
    {
        $obClean = ob_get_clean();

        if ($obClean === false) {
            throw new RuntimeException('Render failed');
        }

        return $obClean;
    }

    /**
     * @inheritDoc
     */
    public function render(string $name, array $variables = []): string
    {
        return $this->createTemplate(name: $name, variables: $variables)->render();
    }

    /**
     * @inheritDoc
     */
    public function createTemplate(string $name, array $variables = []): Template
    {
        return new DefaultTemplate(
            renderer: $this,
            name: $name,
            variables: $variables
        );
    }

    /**
     * @inheritDoc
     */
    public function renderFile(string $name, array $variables = []): string
    {
        return $this->renderFullPath($this->getFullPath($name), $variables);
    }

    /**
     * Render a full file path.
     *
     * @param string               $path      The file path
     * @param array<string, mixed> $variables [optional] The variables
     *
     * @return string
     */
    protected function renderFullPath(string $path, array $variables = []): string
    {
        $this->startRender();
        $this->requirePath($path, $variables);

        return $this->endRender();
    }

    /**
     * Require a path to generate its contents with provided variables.
     *
     * @param string               $path      The file path
     * @param array<string, mixed> $variables [optional] The variables
     *
     * @return void
     */
    protected function requirePath(string $path, array $variables = []): void
    {
        if (is_file($path)) {
            extract($variables, EXTR_SKIP);

            require $path;

            return;
        }

        throw new RuntimeException("Path does not exist at $path");
    }

    /**
     * Get the full path for a given template name.
     *
     * @param string $template The template
     *
     * @throws InvalidConfigPath
     *
     * @return string
     */
    protected function getFullPath(string $template): string
    {
        // If the first character of the template is an @ symbol
        // Then this is a template from a path in the config
        if (str_starts_with($template, '@')) {
            $parts = explode(DIRECTORY_SEPARATOR, $template);
            $path  = $this->paths[$parts[0]] ?? null;

            // If there is no path
            if ($path === null) {
                // Then throw an exception
                throw new InvalidConfigPath(
                    'Invalid path '
                    . $parts[0]
                    . ' specified for template '
                    . $template
                );
            }

            // Remove any trailing slashes
            $parts[0] = DIRECTORY_SEPARATOR . trim($path, DIRECTORY_SEPARATOR);

            $path = implode(DIRECTORY_SEPARATOR, $parts);
        } else {
            $path = $this->getDir($template);
        }

        return $path . $this->fileExtension;
    }

    /**
     * Get the template directory.
     *
     * @param string|null $path [optional] The path to append
     *
     * @return string
     */
    protected function getDir(string|null $path = null): string
    {
        return $this->dir
            . ($path !== null && $path !== ''
                ? DIRECTORY_SEPARATOR . $path
                : '');
    }
}
