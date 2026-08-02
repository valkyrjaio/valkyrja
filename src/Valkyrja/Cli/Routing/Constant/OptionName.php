<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Routing\Constant;

final class OptionName
{
    /** @var non-empty-string */
    public const string HELP = 'help';
    /** @var non-empty-string */
    public const string VERSION = 'version';
    /** @var non-empty-string */
    public const string QUIET = 'quiet';
    /** @var non-empty-string */
    public const string SILENT = 'silent';
    /** @var non-empty-string */
    public const string NO_INTERACTION = 'no-interaction';
    /** @var non-empty-string */
    public const string TOKEN = 'token';
}
