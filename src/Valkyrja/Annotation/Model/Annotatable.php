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

namespace Valkyrja\Annotation\Model;

/**
 * Trait Annotatable.
 *
 * @author Melech Mizrachi
 */
trait Annotatable
{
    /**
     * The type.
     *
     * @var string|null
     */
    public ?string $type;

    /**
     * @inheritDoc
     */
    public function getType(): ?string
    {
        return $this->type ?? null;
    }

    /**
     * @inheritDoc
     *
     * @return static
     */
    public function setType(?string $type = null): static
    {
        $this->type = $type;

        return $this;
    }
}
