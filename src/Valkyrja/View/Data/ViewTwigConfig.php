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

use Twig\Extension\ExtensionInterface;
use Valkyrja\View\Data\Contract\ViewTwigConfigContract;

class ViewTwigConfig implements ViewTwigConfigContract
{
    /**
     * @param array<string, string>              $twigPaths        The named template directories
     * @param class-string<ExtensionInterface>[] $twigExtensions   The twig extensions to add
     * @param non-empty-string                   $twigCompiledPath The directory to write compiled templates to
     */
    public function __construct(
        public readonly array $twigPaths = [],
        public readonly array $twigExtensions = [],
        public readonly string $twigCompiledPath = '/storage/views',
    ) {
    }
}
