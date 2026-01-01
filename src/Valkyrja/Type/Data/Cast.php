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
use Override;
use Valkyrja\Type\Contract\TypeContract;
use Valkyrja\Type\Enum\CastType;

use function assert;

class Cast implements JsonSerializable
{
    /**
     * The type.
     *
     * @var class-string<TypeContract<mixed>>
     */
    public string $type;

    /**
     * @param CastType|class-string<TypeContract<mixed>> $type The type
     */
    public function __construct(
        CastType|string $type,
        public bool $convert = true,
        public bool $isArray = false
    ) {
        /** @var class-string<TypeContract> $type */
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
