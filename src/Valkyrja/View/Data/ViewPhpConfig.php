<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
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
