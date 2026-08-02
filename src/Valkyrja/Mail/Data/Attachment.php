<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Mail\Data;

use Override;
use Valkyrja\Mail\Data\Contract\AttachmentContract;

class Attachment implements AttachmentContract
{
    /**
     * @param non-empty-string $path The path
     */
    public function __construct(
        protected string $path,
        protected string $name = ''
    ) {
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withPath(string $path): static
    {
        $new = clone $this;

        $new->path = $path;

        return $new;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function hasName(): bool
    {
        return $this->name !== '';
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function withName(string $name): static
    {
        $new = clone $this;

        $new->name = $name;

        return $new;
    }
}
