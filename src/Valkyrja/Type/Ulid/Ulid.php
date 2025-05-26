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

namespace Valkyrja\Type\Ulid;

use Exception;
use Valkyrja\Type\Type;
use Valkyrja\Type\Ulid\Contract\Ulid as Contract;
use Valkyrja\Type\Ulid\Support\Ulid as Helper;

/**
 * Class Ulid.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 */
class Ulid extends Type implements Contract
{
    /**
     * @throws Exception
     */
    public function __construct(string|null $subject = null)
    {
        if ($subject !== null) {
            Helper::validate($subject);
        }

        $this->subject = $subject
            ?? Helper::generate();
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
