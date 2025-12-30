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

namespace Valkyrja\Type\Json;

use JsonException;
use Override;
use Valkyrja\Type\Abstract\Type;
use Valkyrja\Type\BuiltIn\Support\Arr as Helper;
use Valkyrja\Type\Json\Contract\Json as Contract;

use function is_string;

/**
 * Class Json.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<array<string|int, mixed>>
 */
class Json extends Type implements Contract
{
    /**
     * @param array<string|int, mixed> $subject The json
     */
    public function __construct(array $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public static function fromValue(mixed $value): static
    {
        if (is_string($value)) {
            return new static(Helper::fromString($value));
        }

        return new static((array) $value);
    }

    /**
     * @inheritDoc
     */
    #[Override]
    public function asValue(): array
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    #[Override]
    public function asFlatValue(): string
    {
        return Helper::toString($this->subject);
    }
}
