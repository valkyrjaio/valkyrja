<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Mail\Data\Contract;

interface RecipientContract
{
    /**
     * Get the email.
     *
     * @return non-empty-string
     */
    public function getEmail(): string;

    /**
     * Create a new instance with the given email.
     *
     * @param non-empty-string $email The email
     */
    public function withEmail(string $email): static;

    /**
     * Determine if there is a name.
     */
    public function hasName(): bool;

    /**
     * Get the name.
     */
    public function getName(): string;

    /**
     * Create a new instance with the given name.
     *
     * @param string $name The name
     */
    public function withName(string $name): static;
}
