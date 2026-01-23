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
use Valkyrja\View\Orka\Replacement\Contract\ReplacementContract;
use Valkyrja\View\Throwable\Exception\RuntimeException;

use function file_get_contents;
use function file_put_contents;
use function is_file;
use function md5;
use function preg_replace;

class OrkaRenderer extends PhpRenderer
{
    /**
     * @var ReplacementContract[]
     */
    protected array $replacements = [];

    /**
     * @param array<non-empty-string, non-empty-string> $paths      The paths
     * @param non-empty-string                          $storageDir The storage directory
     */
    public function __construct(
        string $dir,
        string $fileExtension = '.orka.phtml',
        array $paths = [],
        protected string $storageDir = 'storage/views',
        protected bool $debug = false,
        ReplacementContract ...$replacements
    ) {
        $this->replacements = $replacements;

        parent::__construct(
            dir: $dir,
            fileExtension: $fileExtension,
            paths: $paths,
        );
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function renderFile(string $name, array $variables = []): string
    {
        $cachedPath = $this->getCachedFilePath($name);

        if ($this->debug || ! is_file($cachedPath)) {
            $fileContents = $this->getFileContents($this->getFullPath($name));

            if ($fileContents === false) {
                throw new RuntimeException("Contents for file $name could not be retrieved");
            }

            $contents = $this->parseContent($fileContents);

            file_put_contents($cachedPath, $contents);
        }

        return $this->renderFullPath($cachedPath, $variables);
    }

    /**
     * Parse okra written content to PHP parseable.
     *
     * @param string $contents The contents to parse
     */
    protected function parseContent(string $contents): string
    {
        $replace = [];
        $regexes = [];

        foreach ($this->replacements as $replacement) {
            $replace[$replacement->regex()] = $replacement->replacement();
            $regexes[]                      = $replacement->regex();
        }

        return preg_replace($regexes, $replace, $contents) ?? $contents;
    }

    /**
     * Get the cached file path.
     *
     * @param string $name The name
     */
    protected function getCachedFilePath(string $name): string
    {
        return $this->storageDir . '/' . md5($name);
    }

    /**
     * Get file contents.
     *
     * @param string $path The file path
     *
     * @return string|false
     */
    protected function getFileContents(string $path): string|false
    {
        return file_get_contents($path);
    }
}
