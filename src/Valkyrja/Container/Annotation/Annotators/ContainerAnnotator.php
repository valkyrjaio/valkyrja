<?php

declare(strict_types=1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Container\Annotation\Annotators;

use ReflectionException;
use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\Annotators\Annotator;
use Valkyrja\Application\Application;
use Valkyrja\Container\Annotation\ContainerAnnotator as ContainerAnnotatorContract;
use Valkyrja\Container\Annotation\Service;
use Valkyrja\Container\Annotation\Service\Alias;
use Valkyrja\Container\Annotation\Service\Context;
use Valkyrja\Container\Models\Service as ServiceModel;
use Valkyrja\Container\Models\ServiceContext as ContextServiceModel;
use Valkyrja\Container\Service as ServiceContract;
use Valkyrja\Container\ServiceContext as ContextServiceContract;

/**
 * Class ContainerAnnotator.
 *
 * @author Melech Mizrachi
 */
class ContainerAnnotator extends Annotator implements ContainerAnnotatorContract
{
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
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            ContainerAnnotatorContract::class,
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
        $app->container()->setSingleton(
            ContainerAnnotatorContract::class,
            new static($app)
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
     * @return \Valkyrja\Container\Annotation\Service\Context[]
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
            foreach ($this->getFilter()->classAndMembersAnnotationsByType($type, $class) as $annotation) {
                $this->setServiceProperties($annotation);

                // If this annotation is a service
                if ($type === $this->servicesAnnotationType) {
                    /* @var Service $annotation */
                    $annotations[] = $this->getServiceFromAnnotation($annotation);

                    continue;
                }

                // If this annotation is a context service
                if ($type === $this->contextServicesAnnotationType) {
                    /* @var Context $annotation */
                    $annotations[] = $this->getServiceContextFromAnnotation($annotation);

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
     * @param Annotation $annotation
     *
     * @throws ReflectionException
     *
     * @return void
     */
    protected function setServiceProperties(Annotation $annotation): void
    {
        if (null === $annotation->getProperty() && $annotation->getClass() !== null) {
            $parameters =
                $this->getMethodReflection($annotation->getClass(), $annotation->getMethod() ?? '__construct')
                     ->getParameters();

            // Set the dependencies
            $annotation->setDependencies($this->getDependencies(...$parameters));
        }

        $annotation->setMatches();
    }

    /**
     * Get a service from a service annotation.
     *
     * @param Service $service The service annotation
     *
     * @return ServiceContract
     */
    protected function getServiceFromAnnotation(Service $service): ServiceContract
    {
        return ServiceModel::fromArray($service->asArray());
    }

    /**
     * Get a service context from a service context annotation.
     *
     * @param \Valkyrja\Container\Annotation\Service\Context $service The service context annotation
     *
     * @return ContextServiceContract
     */
    protected function getServiceContextFromAnnotation(Context $service): ContextServiceContract
    {
        return ContextServiceModel::fromArray($service->asArray());
    }
}
