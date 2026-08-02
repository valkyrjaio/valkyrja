<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Cli\Server\Constant;

final class CommandName
{
    /** @var non-empty-string */
    public const string HELP = 'help';
    /** @var non-empty-string */
    public const string LIST = 'list';
    /** @var non-empty-string */
    public const string LIST_BASH = 'list:bash';
    /** @var non-empty-string */
    public const string VERSION = 'version';
    /** @var non-empty-string */
    public const string DATA_GENERATE = 'data:generate';
}
