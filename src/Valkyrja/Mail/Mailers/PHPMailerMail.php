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

namespace Valkyrja\Mail\Mailers;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Valkyrja\Container\Container;
use Valkyrja\Mail\Mail;
use Valkyrja\Container\Support\Provides;

/**
 * Class PHPMailerMail.
 *
 * @author Melech Mizrachi
 */
class PHPMailerMail implements Mail
{
    use Provides;

    /**
     * The PHP Mailer.
     *
     * @var PHPMailer
     */
    protected PHPMailer $phpMailer;

    /**
     * NativeMail constructor.
     *
     * @param PHPMailer $phpMailer
     */
    public function __construct(PHPMailer $phpMailer)
    {
        $this->phpMailer = $phpMailer;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Mail::class,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param Container $container The container
     *
     * @return void
     */
    public static function publish(Container $container): void
    {
        $config     = $container->getSingleton('config');
        $mailConfig = $config['mail'];

        // Create a new instance of the PHPMailer class
        $PHPMailer = new PHPMailer(true);

        // Enable verbose debug output
        $PHPMailer->SMTPDebug = $config['app']['debug'] ? 2 : 0;
        // Set mailer to use SMTP
        $PHPMailer->isSMTP();
        // Specify main and backup SMTP servers
        $PHPMailer->Host = $mailConfig['host'];
        // SMTP Port
        $PHPMailer->Port = $mailConfig['port'];
        // Enable SMTP authentication
        $PHPMailer->SMTPAuth = true;
        // SMTP username
        $PHPMailer->Username = $mailConfig['username'];
        // SMTP password
        $PHPMailer->Password = $mailConfig['password'];
        // Enable TLS encryption, `ssl` also accepted
        $PHPMailer->SMTPSecure = $mailConfig['encryption'];

        $container->setSingleton(
            Mail::class,
            new static(
                $PHPMailer
            )
        );
    }

    /**
     * Set the mail's sender information.
     *
     * @param string $address
     * @param string $name
     *
     * @throws Exception
     *
     * @return bool
     */
    public function setFrom(string $address, string $name = ''): bool
    {
        return $this->phpMailer->setFrom($address, $name);
    }

    /**
     * Add a recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @throws Exception
     *
     * @return bool
     */
    public function addAddress(string $address, string $name = ''): bool
    {
        return $this->phpMailer->addAddress($address, $name);
    }

    /**
     * Add a Reply-To address.
     *
     * @param string $address
     * @param string $name
     *
     * @throws Exception
     *
     * @return bool
     */
    public function addReplyTo(string $address, string $name = ''): bool
    {
        return $this->phpMailer->addReplyTo($address, $name);
    }

    /**
     * Add a copy recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @throws Exception
     *
     * @return bool
     */
    public function addCC(string $address, string $name = ''): bool
    {
        return $this->phpMailer->addCC($address, $name);
    }

    /**
     * Add a blind copy recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @throws Exception
     *
     * @return bool
     */
    public function addBCC(string $address, string $name = ''): bool
    {
        return $this->phpMailer->addBCC($address, $name);
    }

    /**
     * Add an attachment from the filesystem.
     *
     * @param string $path
     * @param string $name
     *
     * @throws Exception
     *
     * @return bool
     */
    public function addAttachment(string $path, string $name = ''): bool
    {
        return $this->phpMailer->addAttachment($path, $name);
    }

    /**
     * Set whether this is an html message.
     *
     * @param bool $isHTML
     *
     * @return static
     */
    public function isHTML(bool $isHTML = true): self
    {
        $this->phpMailer->isHTML($isHTML);

        return $this;
    }

    /**
     * Set the subject.
     *
     * @param string $subject
     *
     * @return static
     */
    public function setSubject(string $subject): self
    {
        $this->phpMailer->Subject = $subject;

        return $this;
    }

    /**
     * Set the body of the mail.
     *
     * @param string $body
     *
     * @return static
     */
    public function setBody(string $body): self
    {
        $this->phpMailer->Body = $body;

        return $this;
    }

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param string $body
     *
     * @return static
     */
    public function setPlainBody(string $body): self
    {
        $this->phpMailer->AltBody = $body;

        return $this;
    }

    /**
     * Send the mail.
     *
     * @throws Exception
     *
     * @return bool
     */
    public function send(): bool
    {
        return $this->phpMailer->send();
    }
}
