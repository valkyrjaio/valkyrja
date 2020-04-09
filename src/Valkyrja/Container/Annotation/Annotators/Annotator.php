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

namespace Valkyrja\Container\Annotation\Annotators;

use ReflectionException;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Filter;
use Valkyrja\Container\Annotation\Annotator as Contract;
use Valkyrja\Container\Annotation\Service;
use Valkyrja\Container\Annotation\Service\Alias;
use Valkyrja\Container\Annotation\Service\Context;
use Valkyrja\Container\Container;
use Valkyrja\Reflection\Reflector;
use Valkyrja\Container\Support\Provides;

/**
 * Class ContainerAnnotator.
 *
 * @author Melech Mizrachi
 */
class Annotator implements Contract
{
    use Provides;

    /**
     * The filter.
     *
     * @var Filter
     */
    protected Filter $filter;

    /**
     * The reflector.
     *
     * @var Reflector
     */
    protected Reflector $reflector;

    /**
     * The services annotation type.
     *
     * @var string
     */
    protected string $servicesAnnotationType = 'Service';

    /**
     * The service alias annotation type.
     *
     * @var string
     */
    protected string $aliasServicesAnnotationType = 'ServiceAlias';

    /**
     * The service context annotation type.
     *
     * @var string
     */
    protected string $contextServicesAnnotationType = 'ServiceContext';

    /**
     * ContainerAnnotator constructor.
     *
     * @param Filter    $filter
     * @param Reflector $reflector
     */
    public function __construct(Filter $filter, Reflector $reflector)
    {
        $this->filter    = $filter;
        $this->reflector = $reflector;
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
        $container->setSingleton(
            Contract::class,
            new static(
                $container->getSingleton(Filter::class),
                $container->getSingleton(Reflector::class)
            )
        );
    }

    /**
     * Get the services.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return Service[]
     */
    public function getServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->servicesAnnotationType, ...$classes);
    }

    /**
     * Get the alias services.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return Alias[]
     */
    public function getAliasServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->aliasServicesAnnotationType, ...$classes);
    }

    /**
     * Get the context services.
     *
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return Context[]
     */
    public function getContextServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->contextServicesAnnotationType, ...$classes);
    }

    /**
     * Get all annotations for a class and its members by type.
     *
     * @param string $type       The type
     * @param string ...$classes The classes
     *
     * @throws ReflectionException
     *
     * @return array
     */
    protected function getAllClassesAnnotationsByType(string $type, string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var Annotation $annotation */
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
        if (null === $annotation->getProperty() && $annotation->getClass() !== null) {
            $reflection = $this->reflector->getMethodReflection(
                $annotation->getClass(),
                $annotation->getMethod() ?? '__construct'
            );

            // Set the dependencies
            $annotation->setDependencies($this->reflector->getDependencies($reflection));
        }

        $annotation->setMatches();
    }
}
