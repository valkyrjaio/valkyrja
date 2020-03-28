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
use Valkyrja\Config\Models\Model;

use function env;

/**
 * Class Mail
 *
 * @author Melech Mizrachi
 */
class Mail extends Model
{
    /**
     * The host.
     *
     * @var string
     */
    public string $host;

    /**
     * The port.
     *
     * @var int
     */
    public int $port;

    /**
     * The from address.
     *
     * @var string
     */
    public string $fromAddress;

    /**
     * The from name.
     *
     * @var string
     */
    public string $fromName;

    /**
     * The encryption.
     *
     * @var string
     */
    public string $encryption;

    /**
     * The username.
     *
     * @var string
     */
    public string $username;

    /**
     * The password.
     *
     * @var string
     */
    public string $password;

    /**
     * Mail constructor.
     *
     * @param bool $setDefaults [optional]
     */
    public function __construct(bool $setDefaults = true)
    {
        if (! $setDefaults) {
            return;
        }

        $this->setHost();
        $this->setPort();
        $this->setFromAddress();
        $this->setFromName();
        $this->setEncryption();
        $this->setUsername();
        $this->setPassword();
    }

    /**
     * Set the host.
     *
     * @param string $host [optional] The host
     *
     * @return void
     */
    protected function setHost(string $host = 'smtp1.example.com;smtp2.example.com'): void
    {
        $this->host = (string) env(EnvKey::MAIL_HOST, $host);
    }

    /**
     * Set the port.
     *
     * @param int $port [optional] The port
     *
     * @return void
     */
    protected function setPort(int $port = 587): void
    {
        $this->port = (int) env(EnvKey::MAIL_PORT, $port);
    }

    /**
     * Set the from address.
     *
     * @param string $fromAddress [optional] The from address
     *
     * @return void
     */
    protected function setFromAddress(string $fromAddress = 'hello@example.com'): void
    {
        $this->fromAddress = (string) env(EnvKey::MAIL_FROM_ADDRESS, $fromAddress);
    }

    /**
     * Set the from name.
     *
     * @param string $fromName [optional] The from name
     *
     * @return void
     */
    protected function setFromName(string $fromName = 'Example'): void
    {
        $this->fromName = (string) env(EnvKey::MAIL_FROM_NAME, $fromName);
    }

    /**
     * Set the encryption.
     *
     * @param string $encryption [optional] The encryption
     *
     * @return void
     */
    protected function setEncryption(string $encryption = 'tls'): void
    {
        $this->encryption = (string) env(EnvKey::MAIL_ENCRYPTION, $encryption);
    }

    /**
     * Set the username.
     *
     * @param string $username [optional] The username
     *
     * @return void
     */
    protected function setUsername(string $username = ''): void
    {
        $this->username = (string) env(EnvKey::MAIL_USERNAME, $username);
    }

    /**
     * Set the password.
     *  NOTE: Recommended to let this come from env
     *
     * @param string $password [optional] The password
     *
     * @return void
     */
    protected function setPassword(string $password = ''): void
    {
        $this->password = (string) env(EnvKey::MAIL_PASSWORD, $password);
    }
}
