<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Validation\Rule\Contract;

interface RuleContract
{
    /**
     * Get the subject.
     */
    public function getSubject(): mixed;

    /**
     * Determine if the rule is valid with the given subject.
     */
    public function isValid(): bool;

    /**
     * Validate the subject.
     */
    public function validate(): void;
}
