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

namespace Valkyrja\Mail\Data;

use Override;
use Valkyrja\Mail\Data\Contract\RecipientContract;

class Recipient implements RecipientContract
{
    /**
     * @param non-empty-string      $email The email
     * @param non-empty-string|null $name  The name
     */
    public function __construct(
        protected string $email,
        protected string|null $name = null
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withEmail(string $email): static
    {
        $new = clone $this;

        $new->email = $email;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string|null
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string|null $name = null): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }
}
