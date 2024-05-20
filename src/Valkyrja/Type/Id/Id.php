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

namespace Valkyrja\Type\Id;

use Valkyrja\Type\Id\Contract\Id as Contract;
use Valkyrja\Type\Type;

/**
 * Class Id.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string|int>
 */
class Id extends Type implements Contract
{
    public function __construct(string|int $subject)
    {
        parent::__construct($subject);
    }

    /**
     * @inheritDoc
     */
    public static function fromValue(mixed $value): static
    {
        if (is_numeric($value)) {
            return new static((int) $value);
        }

        return new static((string) $value);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): string|int
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string|int
    {
        return $this->asValue();
    }
}
