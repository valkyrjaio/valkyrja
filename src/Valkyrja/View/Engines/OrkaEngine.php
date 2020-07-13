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

use function array_keys;
use function file_get_contents;
use function file_put_contents;
use function is_file;
use function md5;
use function preg_replace;

/**
 * Class OrkaEngine.
 *
 * @author Melech Mizrachi
 */
class OrkaEngine extends PHPEngine
{
    protected static array $replace = [
        // @layout
        '/@layout\(\s*\'([a-zA-Z0-9]*)\'\s*\)/x'     => '<?php $template->setLayout(\'${1}\'); ?>',
        '/@block\(\s*\'([a-zA-Z0-9]*)\'\s*\)/x'      => '<?= $template->getBlock(\'${1}\'); ?>',
        '/@startblock\(\s*\'([a-zA-Z0-9]*)\'\s*\)/x' => '<?php $template->startBlock(\'${1}\'); ?>',
        '/@endblock/x'                               => '<?php $template->endBlock(); ?>',
        // {{{ unescaped }}}
        '/\{\{\{\s*(.*)\s*\}\}\}/x'                  => '<?= ${1}; ?>',
        // {{ escaped }}
        '/\{\{\s*(.*)\s*\}\}/x'                      => '<?= $template->escape(${1}); ?>',
        '/@if\(\s*(.*)\s*\)/x'                       => '<?php if (${1}) : ?>',
        '/@elseif\(\s*(.*)\s*\)/x'                   => '<?php elseif (${1}) : ?>',
        '/@else/x'                                   => '<?php else : ?>',
        '/@endif/x'                                  => '<?php endif; ?>',
        '/@unless\(\s*(.*)\s*\)/x'                   => '<?php if (! (${1})) : ?>',
        '/@elseunless\(\s*(.*)\s*\)/x'               => '<?php elseif (! (${1})) : ?>',
        '/@unless/x'                                 => '<?php endif; ?>',
        '/@foreach\(\s*(.*)\s*\)/x'                  => '<?php foreach (${1}) : ?>',
        '/@endforeach/x'                             => '<?php endforeach; ?>',
    ];

    /**
     * OrkaEngine constructor.
     *
     * @param array $config The config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->fileExtension = $config['disks']['orka']['fileExtension'] ?? '.orka.phtml';
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
        $cachedPath = $this->getCachedFilePath($name);

        if (! is_file($cachedPath)) {
            $contents = $this->parseContent(file_get_contents($this->getFullPath($name)));

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
        $contents = preg_replace(array_keys(self::$replace), self::$replace, $contents);

        return $contents;
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
