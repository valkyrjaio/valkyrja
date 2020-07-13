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

use Valkyrja\Support\Directory;
use Valkyrja\View\Engine;
use Valkyrja\View\Exceptions\InvalidConfigPath;

use function explode;
use function extract;
use function implode;
use function ob_get_clean;
use function ob_start;
use function strpos;
use function trim;

use const EXTR_OVERWRITE;

/**
 * Class PHPEngine.
 *
 * @author Melech Mizrachi
 */
class PHPEngine implements Engine
{
    /**
     * The template directory.
     *
     * @var string
     */
    protected string $templateDir;

    /**
     * The file extension.
     *
     * @var string
     */
    protected string $fileExtension;

    /**
     * The view variables.
     *
     * @var array
     */
    protected array $variables = [];

    /**
     * The config.
     *
     * @var array
     */
    protected array $config;

    /**
     * PHPEngine constructor.
     *
     * @param array $config The config
     */
    public function __construct(array $config)
    {
        $this->config        = $config;
        $this->templateDir   = $config['dir'];
        $this->fileExtension = $config['disks']['php']['fileExtension'] ?? '.phtml';
    }

    /**
     * Start rendering.
     *
     * @return void
     */
    public function startRender(): void
    {
        ob_start();
    }

    /**
     * End rendering.
     *
     * @return string
     */
    public function endRender(): string
    {
        return ob_get_clean();
    }

    /**
     * Render a file.
     *
     * @param string $name      The file name
     * @param array  $variables [optional] The variables
     *
     * @return string
     */
    public function renderFile(string $name, array $variables = []): string
    {
        return $this->renderFullPath($this->getFullPath($name), $variables);
    }

    /**
     * Render a full file path.
     *
     * @param string $path      The file path
     * @param array  $variables [optional] The variables
     *
     * @return string
     */
    protected function renderFullPath(string $path, array $variables = []): string
    {
        extract($variables, EXTR_OVERWRITE);

        $this->startRender();

        include $path;

        return $this->endRender();
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
        if (strpos($template, '@') === 0) {
            $explodeOn = Directory::DIRECTORY_SEPARATOR;
            $parts     = explode($explodeOn, $template);
            $path      = $this->config['paths'][$parts[0]] ?? null;

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
            $parts[0] = $explodeOn . trim($path, $explodeOn);

            $path = implode($explodeOn, $parts);
        } else {
            $path = $this->getDir($template);
        }

        return $path . $this->fileExtension;
    }

    /**
     * Get the template directory.
     *
     * @param string $path [optional] The path to append
     *
     * @return string
     */
    protected function getDir(string $path = null): string
    {
        return $this->templateDir . ($path
                ? Directory::DIRECTORY_SEPARATOR . $path
                : $path);
    }
}
