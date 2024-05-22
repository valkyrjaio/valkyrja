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

namespace Valkyrja\Auth\Entity;

use Valkyrja\Auth\Constant\UserField;

/**
 * Trait VerifiableUserTrait.
 *
 * @author Melech Mizrachi
 */
trait VerifiableUserTrait
{
    /**
     * @inheritDoc
     */
    public static function getIsVerifiedField(): string
    {
        return UserField::IS_VERIFIED;
    }
}
