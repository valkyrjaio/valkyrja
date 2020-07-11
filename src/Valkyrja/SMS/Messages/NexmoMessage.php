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

namespace Valkyrja\SMS\Messages;

use Exception;
use Nexmo\Client as Nexmo;
use Nexmo\Client\Credentials\Basic;
use Nexmo\Exception\Request as RequestException;
use Nexmo\Exception\Server as ServerException;
use Valkyrja\Container\Container;
use Valkyrja\Container\Support\Provides;
use Valkyrja\SMS\Message as Contract;

/**
 * Class NexmoMessage.
 *
 * @author Melech Mizrachi
 */
class NexmoMessage implements Contract
{
    use Provides;

    /**
     * The Nexmo client.
     *
     * @var Nexmo
     */
    protected Nexmo $nexmo;

    /**
     * The message to.
     *
     * @var string
     */
    protected string $to;

    /**
     * The message from.
     *
     * @var string
     */
    protected string $from;

    /**
     * The message text.
     *
     * @var string
     */
    protected string $text;

    /**
     * The type of message.
     *
     * @var string|null
     */
    protected ?string $type = null;

    /**
     * NexmoMessage constructor.
     *
     * @param Nexmo $nexmo The Nexmo client
     */
    public function __construct(Nexmo $nexmo)
    {
        $this->nexmo = $nexmo;
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            Contract::class,
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
        $config    = $container->getSingleton('config');
        $smsConfig = $config['sms'];

        $nexmo = new Nexmo(
            new Basic($smsConfig['username'], $smsConfig['password'])
        );

        $container->setSingleton(
            Contract::class,
            new static(
                $nexmo
            )
        );
    }

    /**
     * Make a new message.
     *
     * @return static
     */
    public function make(): self
    {
        return new static(clone $this->nexmo);
    }

    /**
     * Set who to send to.
     *
     * @param string $to The to
     *
     * @return static
     */
    public function setTo(string $to): self
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Set the from.
     *
     * @param string $from The from
     *
     * @return static
     */
    public function setFrom(string $from): self
    {
        $this->from = $from;

        return $this;
    }

    /**
     * Set the text.
     *
     * @param string $text The text
     *
     * @return static
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        $this->type = null;

        return $this;
    }

    /**
     * Set unicode text.
     *
     * @param string $unicodeText The unicode text
     *
     * @return static
     */
    public function setUnicodeText(string $unicodeText): self
    {
        $this->text = $unicodeText;
        $this->type = 'unicode';

        return $this;
    }

    /**
     * Send the mail.
     *
     * @return bool
     */
    public function send(): bool
    {
        try {
            $message  = $this->nexmo->message()->send(
                [
                    'to'   => $this->to,
                    'from' => $this->from,
                    'text' => $this->text,
                    'type' => $this->type,
                ]
            );
            $response = $message->getResponseData();

            if ((int) $response['messages'][0]['status'] === 0) {
                return true;
            }
        } catch (RequestException $e) {
        } catch (ServerException $e) {
        } catch (Exception $e) {
        }

        return false;
    }
}
