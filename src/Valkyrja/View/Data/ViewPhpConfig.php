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

namespace Valkyrja\View\Data;

use Valkyrja\View\Data\Contract\ViewPhpConfigContract;

class ViewPhpConfig implements ViewPhpConfigContract
{
    /**
     * @param non-empty-string      $phpPath          The directory that holds the templates
     * @param non-empty-string      $phpFileExtension The file extension of a template
     * @param array<string, string> $phpPaths         The extra named template directories
     */
    public function __construct(
        public readonly string $phpPath = '/resources/views',
        public readonly string $phpFileExtension = '.phtml',
        public readonly array $phpPaths = [],
    ) {
    }
}
