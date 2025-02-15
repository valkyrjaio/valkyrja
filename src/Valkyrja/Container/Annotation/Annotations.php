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

namespace Valkyrja\Container\Annotation;

use ReflectionException;
use Valkyrja\Annotation\Constant\AnnotationName;
use Valkyrja\Annotation\Filter\Contract\Filter;
use Valkyrja\Annotation\Model\Contract\Annotation;
use Valkyrja\Container\Annotation\Contract\Annotations as Contract;
use Valkyrja\Container\Annotation\Service\Alias;
use Valkyrja\Container\Annotation\Service\Context;
use Valkyrja\Reflection\Contract\Reflection;

/**
 * Class Annotations.
 *
 * @author Melech Mizrachi
 */
class Annotations implements Contract
{
    /**
     * ContainerAnnotator constructor.
     */
    public function __construct(
        protected Filter $filter,
        protected Reflection $reflection
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function getServices(string ...$classes): array
    {
        /** @var Service[] $annotations */
        $annotations = $this->getAllClassesAnnotationsByType(AnnotationName::SERVICE, ...$classes);

        return $annotations;
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function getAliasServices(string ...$classes): array
    {
        /** @var Alias[] $annotations */
        $annotations = $this->getAllClassesAnnotationsByType(AnnotationName::SERVICE_ALIAS, ...$classes);

        return $annotations;
    }

    /**
     * @inheritDoc
     *
     * @throws ReflectionException
     */
    public function getContextServices(string ...$classes): array
    {
        /** @var Context[] $annotations */
        $annotations = $this->getAllClassesAnnotationsByType(AnnotationName::SERVICE_CONTEXT, ...$classes);

        return $annotations;
    }

    /**
     * Get all annotations for a class and its members by type.
     *
     * @param string       $type       The type
     * @param class-string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return Annotation[]
     */
    protected function getAllClassesAnnotationsByType(string $type, string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            foreach ($this->filter->classAndMembersAnnotationsByType($type, $class) as $annotation) {
                $this->setServiceProperties($annotation);

                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a service annotation.
     *
     * @param Annotation $annotation
     *
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setServiceProperties(Annotation $annotation): void
    {
        $class    = $annotation->getClass();
        $property = $annotation->getProperty();

        if ($class && $property === null) {
            $reflection = $this->reflection->forClassMethod(
                $class,
                $annotation->getMethod() ?? '__construct'
            );

            // Set the dependencies
            $annotation->setDependencies($this->reflection->getDependencies($reflection));
        }

        $annotation->setMatches();
    }
}
