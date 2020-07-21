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

/**
 * Interface Message.
 *
 * @author Melech Mizrachi
 */
interface Message
{
    public function getChannel(): string;
    public function setChannel(string $channel): self;

    public function getEvent(): string;
    public function setEvent(string $event): self;

    public function getData(): ?array;
    public function setData(array $data = null): self;

    public function getMessage(): string;
    public function setMessage(string $message): self;
}
