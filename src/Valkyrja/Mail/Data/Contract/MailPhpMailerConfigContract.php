<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Mail\Data\Contract;

interface MailPhpMailerConfigContract
{
    public string $phpMailerHost {
        get;
    }

    public int $phpMailerPort {
        get;
    }

    public string $phpMailerUsername {
        get;
    }

    public string $phpMailerPassword {
        get;
    }

    public string $phpMailerEncryption {
        get;
    }
}
