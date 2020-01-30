<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Valkyrja\Application;
use Valkyrja\Config\Enums\ConfigKeyPart;
use Valkyrja\Support\Providers\Provides;

/**
 * Class PHPMailerMail.
 *
 * @author Melech Mizrachi
 */
class PHPMailerMail implements Mail
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * The PHP Mailer.
     *
     * @var PHPMailer
     */
    protected PHPMailer $PHPMailer;

    /**
     * NativeMail constructor.
     *
     * @param Application $app
     * @param PHPMailer   $PHPMailer
     */
    public function __construct(Application $app, PHPMailer $PHPMailer)
    {
        $this->app       = $app;
        $this->PHPMailer = $PHPMailer;
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
        return $this->PHPMailer->setFrom($address, $name);
    }

    /**
     * Add a recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addAddress(string $address, string $name = ''): bool
    {
        return $this->PHPMailer->addAddress($address, $name);
    }

    /**
     * Add a Reply-To address.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addReplyTo(string $address, string $name = ''): bool
    {
        return $this->PHPMailer->addReplyTo($address, $name);
    }

    /**
     * Add a copy recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addCC(string $address, string $name = ''): bool
    {
        return $this->PHPMailer->addCC($address, $name);
    }

    /**
     * Add a blind copy recipient.
     *
     * @param string $address
     * @param string $name
     *
     * @return bool
     */
    public function addBCC(string $address, string $name = ''): bool
    {
        return $this->PHPMailer->addBCC($address, $name);
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
        return $this->PHPMailer->addAttachment($path, $name);
    }

    /**
     * Set whether this is an html message.
     *
     * @param bool $isHTML
     *
     * @return void
     */
    public function isHTML(bool $isHTML = true): void
    {
        $this->PHPMailer->isHTML($isHTML);
    }

    /**
     * Set the subject.
     *
     * @param string $subject
     *
     * @return void
     */
    public function subject(string $subject): void
    {
        $this->PHPMailer->Subject = $subject;
    }

    /**
     * Set the body of the mail.
     *
     * @param string $body
     *
     * @return void
     */
    public function body(string $body): void
    {
        $this->PHPMailer->Body = $body;
    }

    /**
     * If sending html, add an alternative plain message body for clients without html support.
     *
     * @param string $body
     *
     * @return void
     */
    public function plainBody(string $body): void
    {
        $this->PHPMailer->AltBody = $body;
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
        return $this->PHPMailer->send();
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
     * @param Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        // Create a new instance of the PHPMailer class
        $PHPMailer = new PHPMailer(true);

        // Enable verbose debug output
        $PHPMailer->SMTPDebug = $app->debug() ? 2 : 0;
        // Set mailer to use SMTP
        $PHPMailer->isSMTP();
        // Specify main and backup SMTP servers
        $PHPMailer->Host = $app->config()[ConfigKeyPart::MAIL][ConfigKeyPart::HOST];
        // SMTP Port
        $PHPMailer->Port = $app->config()[ConfigKeyPart::MAIL][ConfigKeyPart::PORT];
        // Enable SMTP authentication
        $PHPMailer->SMTPAuth = true;
        // SMTP username
        $PHPMailer->Username = $app->config()[ConfigKeyPart::MAIL][ConfigKeyPart::USERNAME];
        // SMTP password
        $PHPMailer->Password = $app->config()[ConfigKeyPart::MAIL][ConfigKeyPart::PASSWORD];
        // Enable TLS encryption, `ssl` also accepted
        $PHPMailer->SMTPSecure = $app->config()[ConfigKeyPart::MAIL][ConfigKeyPart::ENCRYPTION];

        $app->container()->singleton(
            Mail::class,
            new static($app, $PHPMailer)
        );
    }
}
