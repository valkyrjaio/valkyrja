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

final class OptionShortName
{
    /** @var non-empty-string */
    public const string HELP = 'h';
    /** @var non-empty-string */
    public const string VERSION = 'v';
    /** @var non-empty-string */
    public const string QUIET = 'q';
    /** @var non-empty-string */
    public const string SILENT = 's';
    /** @var non-empty-string */
    public const string NO_INTERACTION = 'N';
    /** @var non-empty-string */
    public const string TOKEN = 't';
}
