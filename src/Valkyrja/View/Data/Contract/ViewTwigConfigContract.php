<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\View\Data\Contract;

use Twig\Extension\ExtensionInterface;

interface ViewTwigConfigContract
{
    /** @var array<string, string> */
    public array $twigPaths {
        get;
    }

    /** @var class-string<ExtensionInterface>[] */
    public array $twigExtensions {
        get;
    }

    /** @var non-empty-string */
    public string $twigCompiledPath {
        get;
    }
}
