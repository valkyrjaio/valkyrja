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

namespace Valkyrja\Type\Uuid;

use Exception;
use Valkyrja\Type\Type;
use Valkyrja\Type\Uuid\Contract\Uuid as Contract;
use Valkyrja\Type\Uuid\Support\Uuid as Helper;

/**
 * Class Uuid.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 *
 * @implements Contract<string>
 */
class Uuid extends Type implements Contract
{
    /**
     * @throws Exception
     */
    public function __construct(string $subject)
    {
        Helper::validate($subject);

        $this->subject = $subject;
    }

    /**
     * @inheritDoc
     *
     * @throws Exception
     */
    public static function fromValue(mixed $value): static
    {
        return new static($value);
    }

    /**
     * @inheritDoc
     */
    public function asValue(): string
    {
        return $this->subject;
    }

    /**
     * @inheritDoc
     */
    public function asFlatValue(): string
    {
        return $this->asValue();
    }
}
