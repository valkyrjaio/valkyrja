<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Type\Data;

use JsonSerializable;
use Override;
use Valkyrja\Type\Contract\TypeContract;
use Valkyrja\Type\Enum\CastType;

use function assert;
use function get_object_vars;
use function is_a;

class Cast implements JsonSerializable
{
    /**
     * The type.
     *
     * @var class-string
     */
    public string $type;

    /**
     * @param CastType|class-string $type The type
     */
    public function __construct(
        CastType|string $type,
        public bool $convert = true,
        public bool $isArray = false
    ) {
        /** @var class-string $type */
        $type = ($type instanceof CastType)
            ? $type->value
            : $type;

        $this->type = $type;

        assert(is_a($this->type, TypeContract::class, true));
    }

    /**
     * @inheritDoc
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
