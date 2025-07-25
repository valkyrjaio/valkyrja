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

use Override;
use Valkyrja\Support\Directory;
use Valkyrja\View\Exception\RuntimeException;

use function array_keys;
use function file_get_contents;
use function file_put_contents;
use function is_file;
use function md5;
use function preg_replace;

/**
 * Class OrkaRenderer.
 *
 * @author Melech Mizrachi
 */
class OrkaRenderer extends PhpRenderer
{
    /**
     * @var array<string, string>
     */
    protected static array $replace = [
        // @layout
        '/@layout\s*\(\s*(.*)\s*\)/x'              => '<?php $template->setLayout(${1}); ?>',
        '/@block\s*\(\s*(.*)\s*\)/x'               => '<?= $template->getBlock(${1}); ?>',
        '/@trimblock\s*\(\s*(.*)\s*\)/x'           => '<?= trim($template->getBlock(${1})); ?>',
        '/@startblock\s*\(\s*(.*)\s*\)/x'          => '<?php $template->startBlock(${1}); ?>',
        '/@endblock/x'                             => '<?php $template->endBlock(); ?>',
        '/@hasblock\s*\(\s*(.*)\s*\)/x'            => '<?php if ($template->hasBlock(${1})) : ?>',
        '/@elsehasblock/x'                         => '<?php else : ?>',
        '/@elseifhasblock\s*\(\s*(.*)\s*\)/x'      => '<?php elseif ($template->hasBlock(${1})) : ?>',
        '/@endhasblock/x'                          => '<?php endif ?>',
        '/@unlessblock\s*\(\s*(.*)\s*\)/x'         => '<?php if (! $template->hasBlock(${1})) : ?>',
        '/@endunlessblock/x'                       => '<?php endif ?>',
        '/@partial\s*\(\s*(.*)\s*\s*\)/x'          => '<?= $template->getPartial(${1}); ?>',
        '/@partial\s*\(\s*(.*)\s*,(.*)\s*\)/x'     => '<?= $template->getPartial(${1}, ${2}); ?>',
        '/@trimpartial\s*\(\s*(.*)\s*\s*\)/x'      => '<?= trim($template->getPartial(${1})); ?>',
        '/@trimpartial\s*\(\s*(.*)\s*,(.*)\s*\)/x' => '<?= trim($template->getPartial(${1}, ${2})); ?>',
        '/@setvariables\s*\(\s*(.*)\s*\s*\)/x'     => '<?php $template->setVariables(${1}); ?>',
        '/@setvariable\s*\(\s*(.*)\s*,(.*)\s*\)/x' => '<?php $template->setVariable(${1}, ${2}); ?>',
        '/@empty\s*\(\s*(.*)\s*\)/x'               => '<?php if (empty(${1})) : ?>',
        '/@notempty\s*\(\s*(.*)\s*\)/x'            => '<?php if (! empty(${1})) : ?>',
        '/@if\s*\(\s*(.*)\s*\)/x'                  => '<?php if (${1}) : ?>',
        '/@elseif\s*\(\s*(.*)\s*\)/x'              => '<?php elseif (${1}) : ?>',
        '/@else/x'                                 => '<?php else : ?>',
        '/@endif/x'                                => '<?php endif ?>',
        '/@isset\s*\(\s*(.*)\s*\)/x'               => '<?php if (isset(${1})) : ?>',
        '/@endisset/x'                             => '<?php endif ?>',
        '/@unless\s*\(\s*(.*)\s*\)/x'              => '<?php if (! (${1})) : ?>',
        '/@elseunless\s*\(\s*(.*)\s*\)/x'          => '<?php elseif (! (${1})) : ?>',
        '/@endunless/x'                            => '<?php endif ?>',
        '/@foreach\s*\(\s*(.*)\s*\)/x'             => '<?php foreach (${1}) : ?>',
        '/@endforeach/x'                           => '<?php endforeach ?>',
        '/@for\s*\(\s*(.*)\s*\)/x'                 => '<?php for (${1}) : ?>',
        '/@endfor/x'                               => '<?php endfor ?>',
        '/@switch\s*\(\s*(.*)\s*\)/x'              => '<?php switch (${1}) :',
        '/@firstcase\s*\(\s*(.*)\s*\)/x'           => 'case ${1} : ?>',
        '/@case\s*\(\s*(.*)\s*\)/x'                => '<?php case ${1} : ?>',
        '/@break/x'                                => '<?php break; ?>',
        '/@default/x'                              => '<?php default : ?>',
        '/@endswitch/x'                            => '<?php endswitch ?>',
        '/@dd\s*\(\s*(.*)\s*\s*\)/x'               => '<?php \Valkyrja\dd(${1}); ?>',
        // {{--
        '/\{\{\-\-/x'                              => '<?php /** ?>',
        // --}}
        '/\-\-\}\}/x'                              => '<?php */ ?>',
        // {{{ unescaped }}}
        '/\{\{\{\s*(.*?)\s*\}\}\}/x'               => '<?= ${1}; ?>',
        // {{ escaped }}
        '/\{\{\s*(.*?)\s*\}\}/x'                   => '<?= $template->escape(${1}); ?>',
    ];

    /**
     * OrkaRenderer constructor.
     *
     * @param array<string, string> $paths
     */
    public function __construct(
        string $dir,
        string $fileExtension = '.orka.phtml',
        array $paths = [],
        protected bool $debug = false,
    ) {
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
            $fileContents = file_get_contents($this->getFullPath($name));

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
     *
     * @return string
     */
    protected function parseContent(string $contents): string
    {
        /** @var non-empty-string[] $regexes */
        $regexes = array_keys(self::$replace);

        return preg_replace($regexes, self::$replace, $contents) ?? $contents;
    }

    /**
     * Get the cached file path.
     *
     * @param string $name The name
     *
     * @return string
     */
    protected function getCachedFilePath(string $name): string
    {
        return Directory::storagePath('views/' . md5($name));
    }
}
