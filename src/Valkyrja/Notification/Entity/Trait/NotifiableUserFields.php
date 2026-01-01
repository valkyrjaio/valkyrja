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

namespace Valkyrja\Notification\Entity\Trait;

trait NotifiableUserFields
{
    /**
     * The name.
     *
     * @var string
     */
    public string $name = '';

    /**
     * The phone number.
     *
     * @var string
     */
    public string $phone_number = '';

    /**
     * The secret id.
     *
     * @var string
     */
    public string $secret_id = '';
}
