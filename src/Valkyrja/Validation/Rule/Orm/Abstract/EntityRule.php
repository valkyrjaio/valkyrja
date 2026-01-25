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

namespace Valkyrja\Validation\Rule\Orm\Abstract;

use Valkyrja\Orm\Data\Value;
use Valkyrja\Orm\Data\Where;
use Valkyrja\Orm\Entity\Contract\EntityContract;
use Valkyrja\Orm\Manager\Contract\ManagerContract;
use Valkyrja\Throwable\Exception\InvalidArgumentException;
use Valkyrja\Validation\Rule\Abstract\Rule;

use function is_bool;
use function is_float;
use function is_int;
use function is_string;

abstract class EntityRule extends Rule
{
    /**
     * @param class-string<EntityContract> $entity       The entity to match
     * @param non-empty-string|null        $field        The field to match
     * @param non-empty-string|null        $errorMessage The error message
     */
    public function __construct(
        protected ManagerContract $orm,
        mixed $subject,
        protected string $entity,
        protected string|null $field = null,
        string|null $errorMessage = null
    ) {
        parent::__construct($subject, $errorMessage);
    }

    protected function checkForEntity(): EntityContract|null
    {
        $subject = $this->subject;
        $entity  = $this->entity;
        $field   = $this->field;

        if ($subject !== null && ! is_string($subject) && ! is_int($subject) && ! is_float($subject) && ! is_bool($subject)) {
            throw new InvalidArgumentException('Value to match must be string, int, float, bool, or null');
        }

        $field ??= $entity::getIdField();

        // Check for a result
        return $this->orm->createRepository($entity)->findBy(new Where(new Value(name: $field, value: $subject)));
    }
}
