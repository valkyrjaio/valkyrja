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

namespace Valkyrja\Routing\Processors;

use BackedEnum;
use InvalidArgumentException;
use Valkyrja\Dispatcher\Validator;
use Valkyrja\Orm\Entity;
use Valkyrja\Routing\Constants\Regex;
use Valkyrja\Routing\Enums\CastType;
use Valkyrja\Routing\Exceptions\InvalidRoutePath;
use Valkyrja\Routing\Models\Parameter;
use Valkyrja\Routing\Processor as Contract;
use Valkyrja\Routing\Route;
use Valkyrja\Routing\Support\Helpers;
use Valkyrja\Type\Str;

use function assert;

/**
 * Class Processor.
 *
 * @author Melech Mizrachi
 */
class Processor implements Contract
{
    /**
     * Processor constructor.
     *
     * @param Validator $validator
     */
    public function __construct(
        protected Validator $validator,
    ) {
    }

    /**
     * Process a route.
     *
     * @param Route $route The route
     *
     * @throws InvalidRoutePath
     *
     * @return void
     */
    public function route(Route $route): void
    {
        // Verify the route
        $this->verifyRoute($route);
        // Verify the dispatch
        $this->validator->dispatch($route);

        // Set the id to the spl_object_id of the route
        $route->setId((string) spl_object_id($route));
        // Set the id to an md5 hash of the route
        // $route->setId(md5(Arr::toString($route->asArray())));
        // Set the path to the validated cleaned path (/some/path)
        $route->setPath(Helpers::trimPath($route->getPath()));
        // Set whether the route is dynamic
        $route->setDynamic(Str::contains($route->getPath(), '{'));

        // If this is a dynamic route
        if ($route->isDynamic()) {
            $this->modifyRegex($route);
        }
    }

    /**
     * Verify a route.
     *
     * @param Route $route The route
     *
     * @return void
     */
    protected function verifyRoute(Route $route): void
    {
        if (! $route->getPath()) {
            throw new InvalidArgumentException('Invalid path defined in route.');
        }
    }

    /**
     * Create the regex for a route.
     *
     * @param Route $route The route
     *
     * @throws InvalidRoutePath
     *
     * @return string
     */
    protected function modifyRegex(Route $route): string
    {
        // If the regex has already been set then don't do anything
        if ($regex = $route->getRegex()) {
            return $regex;
        }

        // Replace all slashes with \/
        $regex = Str::replace($route->getPath(), '/', Regex::PATH);

        // Iterate through the route's parameters
        foreach ($route->getParameters() as $parameter) {
            // Validate the parameter
            $this->processParameterEntity($route, $parameter);
            $this->processParameterEnum($parameter);
            $this->processParameterInRegex($parameter, $regex);

            $regex = $this->replaceParameterNameInRegex($route, $parameter, $regex);
        }

        $regex = Regex::START . $regex . Regex::END;

        // Set the regex
        $route->setRegex($regex);

        return $regex;
    }

    /**
     * Validate the parameter entity.
     *
     * @param Route     $route     The route
     * @param Parameter $parameter The parameter
     *
     * @return void
     */
    protected function processParameterEntity(Route $route, Parameter $parameter): void
    {
        $entity = $parameter->getEntity();

        if ($entity !== null) {
            assert(is_a($entity, Entity::class, true));

            $this->removeEntityFromDependencies($route, $entity);

            $entityColumn = $parameter->getEntityColumn();

            if ($entityColumn !== null) {
                assert(property_exists($entity, $entityColumn));
            }
        }
    }

    /**
     * Remove the entity from the route's dependencies list.
     *
     * @param Route                $route      The route
     * @param class-string<Entity> $entityName The entity class name
     *
     * @return void
     */
    protected function removeEntityFromDependencies(Route $route, string $entityName): void
    {
        $dependencies = $route->getDependencies();

        if (empty($dependencies)) {
            return;
        }

        $updatedDependencies = [];

        foreach ($dependencies as $dependency) {
            if ($dependency !== $entityName) {
                $updatedDependencies[] = $dependency;
            }
        }

        $route->setDependencies($updatedDependencies);
    }

    /**
     * Validate the parameter enum.
     *
     * @param Parameter $parameter The parameter
     *
     * @return void
     */
    protected function processParameterEnum(Parameter $parameter): void
    {
        $enum = $parameter->getEnum();

        if ($enum !== null) {
            assert(is_a($enum, BackedEnum::class, true));

            // Set the regex to the enum cases
            $parameter->setRegex(implode('|', array_column($enum::cases(), 'value')));
            // Ensure the type case was set properly
            $parameter->setType(CastType::enum);
        }
    }

    /**
     * Validate the parameter name exists in the regex.
     *
     * @param Parameter $parameter The parameter
     * @param string    $regex     The regex
     *
     * @return void
     */
    protected function processParameterInRegex(Parameter $parameter, string $regex): void
    {
        // If the parameter is optional or the name has a ? affixed to it
        if ($parameter->isOptional() || Str::contains($regex, $parameter->getName() . '?')) {
            // Ensure the parameter is set to optional
            $parameter->setIsOptional(true);
        }
    }

    /**
     * Replace the parameter name in the route's regex.
     *
     * @param Route     $route     The route
     * @param Parameter $parameter The parameter
     * @param string    $regex     The regex
     *
     * @throws InvalidRoutePath
     *
     * @return string
     */
    protected function replaceParameterNameInRegex(Route $route, Parameter $parameter, string $regex): string
    {
        // Get whether this parameter is optional
        /** @var bool $isOptional */
        $isOptional = $parameter->isOptional();

        // Get the replacement for this parameter's name (something like {name} or {name?}
        // Prepend \/ if it optional so we can replace the path slash and set it in the
        // regex below as a non-capture-optional group
        $nameReplacement = ($isOptional ? Regex::PATH : '')
            . '{' . $parameter->getName() . ($isOptional ? '?' : '') . '}';

        // Check if the path doesn't contain the parameter's name replacement
        if (! Str::contains($regex, $nameReplacement)) {
            throw new InvalidRoutePath("{$route->getPath()} is missing $nameReplacement");
        }

        // If optional we don't want to capture the / before the value
        $parameterRegex = ($isOptional ? Regex::START_OPTIONAL_CAPTURE_GROUP : '')
            // Start the actual value's capture group
            . (! $parameter->shouldCapture() ? Regex::START_NON_CAPTURE_GROUP : Regex::START_CAPTURE_GROUP)
            // Set the parameter's regex to match the value
            . $parameter->getRegex()
            // End the capture group
            . ($isOptional ? Regex::END_OPTIONAL_CAPTURE_GROUP : Regex::END_CAPTURE_GROUP);

        // Replace the {name} or \/{name?} with the finished regex
        return Str::replace($regex, $nameReplacement, $parameterRegex);
    }
}
