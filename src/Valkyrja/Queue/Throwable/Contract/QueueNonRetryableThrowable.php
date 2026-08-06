<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Queue\Throwable\Contract;

/**
 * Marks a throwable that must not be retried.
 *
 * A retry only helps when the failure is transient. A bad payload or a failed
 * validation will fail identically on every redelivery, so a job that throws
 * one of these gives up on purpose and dead-letters immediately rather than
 * burning its whole attempt budget.
 */
interface QueueNonRetryableThrowable extends QueueThrowable
{
}
