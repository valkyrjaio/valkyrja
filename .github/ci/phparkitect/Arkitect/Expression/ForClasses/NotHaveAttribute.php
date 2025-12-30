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

namespace Valkyrja\Arkitect\Expression\ForClasses;

use Arkitect\Analyzer\ClassDescription;
use Arkitect\Expression\Description;
use Arkitect\Expression\Expression;
use Arkitect\Rules\Violation;
use Arkitect\Rules\ViolationMessage;
use Arkitect\Rules\Violations;

use function var_dump;

final class NotHaveAttribute implements Expression
{
    /** @var string */
    private $attribute;

    public function __construct(string $attribute)
    {
        $this->attribute = $attribute;
    }

    public function describe(ClassDescription $theClass, string $because): Description
    {
        return new Description("should not have the attribute {$this->attribute}", $because);
    }

    public function evaluate(ClassDescription $theClass, Violations $violations, string $because): void
    {
        if (! $theClass->hasAttribute($this->attribute)) {
            return;
        }

        $violations->add(
            Violation::create(
                $theClass->getFQCN(),
                ViolationMessage::selfExplanatory($this->describe($theClass, $because)),
                $theClass->getFilePath()
            )
        );
    }
}
