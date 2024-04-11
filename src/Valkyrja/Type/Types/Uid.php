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

use Exception;
use Valkyrja\Type\Support\Uid as Helper;
use Valkyrja\Type\Uid as Contract;

/**
 * Class Uid.
 *
 * @author Melech Mizrachi
 *
 * @extends Type<string>
 */
class Uid extends Type implements Contract
{
    /**
     * @throws Exception
     */
    public function __construct(string $subject)
    {
        Helper::validate($subject);

        parent::__construct($subject);
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
