<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Config\Configs;

use Valkyrja\Config\Enums\EnvKey;
use Valkyrja\Config\Models\Config as Model;

/**
 * Class Mail.
 *
 * @author Melech Mizrachi
 */
class Mail extends Model
{
    public string $host        = 'smtp1.example.com;smtp2.example.com';
    public int    $port        = 587;
    public string $fromAddress = 'hello@example.com';
    public string $fromName    = 'Example';
    public string $encryption  = 'tls';
    public string $username    = '';
    public string $password    = '';

    /**
     * Mail constructor.
     */
    public function __construct()
    {
        $this->host        = (string) env(EnvKey::MAIL_HOST, $this->host);
        $this->port        = (int) env(EnvKey::MAIL_PORT, $this->port);
        $this->fromAddress = (string) env(EnvKey::MAIL_FROM_ADDRESS, $this->fromAddress);
        $this->fromName    = (string) env(EnvKey::MAIL_FROM_NAME, $this->fromName);
        $this->encryption  = (string) env(EnvKey::MAIL_ENCRYPTION, $this->encryption);
        $this->username    = (string) env(EnvKey::MAIL_USERNAME, $this->username);
        $this->password    = (string) env(EnvKey::MAIL_PASSWORD, $this->password);
    }
}
