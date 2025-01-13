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

namespace Valkyrja\Type\Data;

use JsonSerializable;
use Valkyrja\Type\Contract\Type;
use Valkyrja\Type\Enum\CastType;

use function assert;

/**
 * Data Cast.
 *
 * @author Melech Mizrachi
 */
class Cast implements JsonSerializable
{
    /**
     * The type.
     *
     * @var class-string<Type>
     */
    public string $type;

    /**
     * @param CastType|class-string<Type> $type The type
     */
    public function __construct(
        CastType|string $type,
        public bool $convert = true,
        public bool $isArray = false
    ) {
        /** @var class-string<Type> $type */
        $type = ($type instanceof CastType)
            ? $type->value
            : $type;

        $this->type = $type;

        assert(is_a($this->type, Type::class, true));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
