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

namespace Valkyrja\Broadcast;

use Valkyrja\Auth\User;

/**
 * Interface SecretIdUser.
 *
 * @author Melech Mizrachi
 */
interface SecretIdUser extends User
{
    /**
     * Get the secret id field.
     *
     * @return string
     */
    public static function getSecretIdField(): string;

    /**
     * Get the secret id field value.
     *
     * @return string
     */
    public function getSecretIdFieldValue(): string;

    /**
     * Set the secret id field value.
     *
     * @param string $secretId The secret id
     *
     * @return void
     */
    public function setSecretIdFieldValue(string $secretId): void;
}
