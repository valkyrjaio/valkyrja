<?php

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Annotations;

use Valkyrja\Annotations\Annotations;
use Valkyrja\Container\Enums\CoreComponent;
use Valkyrja\Container\Service as ContainerService;
use Valkyrja\Contracts\Application;
use Valkyrja\Contracts\Container\Annotations\ContainerAnnotations as ContainerAnnotationsContract;
use Valkyrja\Dispatcher\Dispatch;
use Valkyrja\Support\Provides;

/**
 * Class ContainerAnnotations.
 *
 * @author Melech Mizrachi
 */
class ContainerAnnotations extends Annotations implements ContainerAnnotationsContract
{
    use Provides;

    /**
     * THe services annotation type.
     *
     * @var string
     */
    protected $servicesAnnotationType = 'Service';

    /**
     * The service alias annotation type.
     *
     * @var string
     */
    protected $aliasServicesAnnotationType = 'ServiceAlias';

    /**
     * The service context annotation type.
     *
     * @var string
     */
    protected $contextServicesAnnotationType = 'ServiceContext';

    /**
     * Get the services.
     *
     * @param string[] $classes The classes
     *
     * @throws \ReflectionException
     *
     * @return \Valkyrja\Container\Service[]
     */
    public function getServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->servicesAnnotationType, ...$classes);
    }

    /**
     * Get the alias services.
     *
     * @param string[] $classes The classes
     *
     * @throws \ReflectionException
     *
     * @return \Valkyrja\Container\ServiceContext[]
     */
    public function getAliasServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->aliasServicesAnnotationType, ...$classes);
    }

    /**
     * Get the context services.
     *
     * @param string[] $classes The classes
     *
     * @throws \ReflectionException
     *
     * @return \Valkyrja\Container\ServiceContext[]
     */
    public function getContextServices(string ...$classes): array
    {
        return $this->getAllClassesAnnotationsByType($this->contextServicesAnnotationType, ...$classes);
    }

    /**
     * Get all annotations for a class and its members by type.
     *
     * @param string   $type       The type
     * @param string[] ...$classes The classes
     *
     * @throws \ReflectionException
     *
     * @return array
     */
    protected function getAllClassesAnnotationsByType(string $type, string ...$classes): array
    {
        $annotations = [];

        // Iterate through all the classes
        foreach ($classes as $class) {
            // Get all the annotations for each class and iterate through them
            /** @var \Valkyrja\Dispatcher\Dispatch $annotation */
            foreach ($this->classAndMembersAnnotationsType($type, $class) as $annotation) {
                $this->setServiceProperties($annotation);

                // If this annotation is a service
                if ($type === $this->servicesAnnotationType) {
                    /* @var \Valkyrja\Container\Annotations\Service $annotation */
                    $annotations[] = $this->getServiceFromAnnotation($annotation);

                    continue;
                }

                // Set the annotation in the annotations list
                $annotations[] = $annotation;
            }
        }

        return $annotations;
    }

    /**
     * Set the properties for a service annotation.
     *
     * @param \Valkyrja\Dispatcher\Dispatch $dispatch
     *
     * @throws \ReflectionException
     *
     * @return void
     */
    protected function setServiceProperties(Dispatch $dispatch): void
    {
        if (null === $dispatch->getProperty()) {
            $parameters = $this->getMethodReflection($dispatch->getClass(), $dispatch->getMethod() ?? '__construct')
                               ->getParameters();

            // Set the dependencies
            $dispatch->setDependencies($this->getDependencies(...$parameters));
        }

        $dispatch->setMatches();
    }

    /**
     * Get a service from a service annotation.
     *
     * @param \Valkyrja\Container\Annotations\Service $service The service annotation
     *
     * @return \Valkyrja\Container\Service
     */
    protected function getServiceFromAnnotation(Service $service): ContainerService
    {
        return (new ContainerService())
            ->setDefaults($service->getDefaults())
            ->setSingleton($service->isSingleton())
            ->setId($service->getId())
            ->setName($service->getName())
            ->setClass($service->getClass())
            ->setProperty($service->getProperty())
            ->setMethod($service->getMethod())
            ->setStatic($service->isStatic())
            ->setFunction($service->getFunction())
            ->setMatches($service->getMatches())
            ->setClosure($service->getClosure())
            ->setDependencies($service->getDependencies())
            ->setArguments($service->getArguments());
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            CoreComponent::CONTAINER_ANNOTATIONS,
        ];
    }

    /**
     * Publish the provider.
     *
     * @param \Valkyrja\Contracts\Application $app The application
     *
     * @return void
     */
    public static function publish(Application $app): void
    {
        $app->container()->singleton(
            CoreComponent::CONTAINER_ANNOTATIONS,
            new ContainerAnnotations(
                $app->container()->getSingleton(CoreComponent::ANNOTATIONS_PARSER)
            )
        );
    }
}
