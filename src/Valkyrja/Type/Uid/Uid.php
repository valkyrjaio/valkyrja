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

namespace Valkyrja\Type\Uid;

use Exception;
use Valkyrja\Type\Type;
use Valkyrja\Type\Uid\Contract\Uid as Contract;
use Valkyrja\Type\Uid\Support\Uid as Helper;

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
