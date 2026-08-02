<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Http\Message\Stream\Enum;

/**
 * @see https://www.php.net/manual/en/function.fopen.php
 */
enum PhpWrapper: string
{
    case stdin  = 'php://stdin';
    case stdout = 'php://stdout';
    case stderr = 'php://stderr';
    case input  = 'php://input';
    case output = 'php://output';
    case fd     = 'php://fd';
    case memory = 'php://memory';
    case temp   = 'php://temp';
    case filter = 'php://filter';
}
