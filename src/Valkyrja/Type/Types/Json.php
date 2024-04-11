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

namespace Valkyrja\Type\Types;

use JsonException;
use Valkyrja\Type\Support\Arr as Helper;
use Valkyrja\Type\Json as Contract;

/**
 * Class Json.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<array>
 */
class Json extends Type implements Contract
{
    public function __construct(array $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
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
    public function asValue(): array
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     *
     * @throws JsonException
     */
    public function asFlatValue(): string
    {
        return Helper::toString($this->subject);
    }
}
