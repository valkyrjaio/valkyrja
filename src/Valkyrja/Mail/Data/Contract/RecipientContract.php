<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * (c) Melech Mizrachi <melechmizrachi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Mail\Data\Contract;

interface RecipientContract
{
    /**
     * @return non-empty-string
     */
    public function getEmail(): string;

    /**
     * @param non-empty-string $email The email
     */
    public function withEmail(string $email): static;

    /**
     * @return non-empty-string|null
     */
    public function getName(): string|null;

    /**
     * @param non-empty-string|null $name The name
     */
    public function withName(string|null $name = null): static;
}
