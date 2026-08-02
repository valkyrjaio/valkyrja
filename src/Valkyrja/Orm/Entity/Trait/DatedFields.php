<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja Framework package.
 *
 * Copyright (c) 2016-present Melech Mizrachi
 *
 * Released under the MIT License. See LICENSE.md for details.
 */

namespace Valkyrja\Orm\Entity\Trait;

trait DatedFields
{
    /**
     * The date the entity was created.
     *
     * @var string
     */
    public string $created_at;

    /**
     * The date the entity was last modified.
     *
     * @var string
     */
    public string $updated_at;
}
