<?php

declare(strict_types = 1);

/*
 * This file is part of the Valkyrja framework.
 *
 * (c) Melech Mizrachi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Valkyrja\Annotation\Parsers;

use Valkyrja\Annotation\Annotation;
use Valkyrja\Annotation\AnnotationsParser as AnnotationsParserContract;
use Valkyrja\Annotation\Exceptions\InvalidAnnotationKeyArgument;
use Valkyrja\Annotation\Models\Annotation as AnnotationModel;
use Valkyrja\Application\Application;
use Valkyrja\Config\Enums\ConfigKey;
use Valkyrja\Support\Providers\Provides;

/**
 * Class AnnotationsParser.
 *
 * @author Melech Mizrachi
 */
class AnnotationsParser implements AnnotationsParserContract
{
    use Provides;

    /**
     * The application.
     *
     * @var Application
     */
    protected Application $app;

    /**
     * AnnotationsParser constructor.
     *
     * @param Application $application The application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
    }

    /**
     * Get annotations from a given string.
     *
     * @param string $docString The doc string
     *
     * @throws InvalidAnnotationKeyArgument
     *
     * @return Annotation[]
     */
    public function getAnnotations(string $docString): array
    {
        $annotations = [];

        // Get all matches of @ Annotations
        $matches = $this->getMatches($docString);

        // If there are any matches iterate through them and create new
        // annotations
        if ($matches && isset($matches[0], $matches[1])) {
            foreach ($matches[0] as $index => $match) {
                $this->setAnnotation($matches, $index, $annotations);
            }
        }

        return $annotations;
    }

    /**
     * Filter a string of properties into an key => value array.
     *
     * @param string $properties The properties
     *
     * @throws InvalidAnnotationKeyArgument
     *
     * @return array
     */
    public function getPropertiesAsArray(string $properties = null): ?array
    {
        $propertiesList = null;

        // If a valid properties list was passed in
        if (null !== $properties && $properties) {
            $testArgs       = str_replace('=', ':', $properties);
            $propertiesList = json_decode('{' . $testArgs . '}', true, 512, JSON_THROW_ON_ERROR);

            if (is_array($propertiesList)) {
                foreach ($propertiesList as &$value) {
                    $value = $this->determinePropertyValue($value);
                }

                unset($value);
            }

            return $propertiesList;
        }

        return $propertiesList;
    }

    /**
     * Get the annotations regex.
     *
     * @return string
     */
    public function getRegex(): string
    {
        /*
         * @description
         *
         * This regex will produce an array matches list of
         * $matches[0] All matches
         * $matches[1] Annotation name
         *
         * @Annotation($data)
         *
         * $matches[2] Parenthesis enclosed results
         *
         * @Annotation $matches[3] $matches[4] $matches[5]
         * Matches: @method, @param $test, @description description,
         *          @param int $test Description, etc
         * None of the below matches requires the previous one to exist
         *
         * $matches[3] Description or type part of a line annotation
         * $matches[4] Variable part of line annotation
         *             (must be a variable beginning with $)
         * $matches[5] Description part of line annotation
         */
        return '/'
            . self::ANNOTATION_SYMBOL
            . '([a-zA-Z]*)'
            . '(?:' . self::CLASS_REGEX . ')?'
            . self::LINE_REGEX
            . '/x';
    }

    /**
     * Get the properties regex.
     *
     * @return string
     */
    public function getArgumentsRegex(): string
    {
        return '/' . static::ARGUMENTS_REGEX . '/x';
    }

    /**
     * Get the annotations map.
     *
     * @return array
     */
    public function getAnnotationsMap(): array
    {
        return $this->app->config(ConfigKey::ANNOTATIONS_MAP);
    }

    /**
     * Get an annotation model from the annotations map.
     *
     * @param string $annotationType The annotation type
     *
     * @return Annotation
     */
    public function getAnnotationFromMap(string $annotationType): Annotation
    {
        // Get the annotations map (annotation name to annotation class)
        $annotationsMap = $this->getAnnotationsMap();

        // If an annotation is mapped to a class
        if ($annotationType && array_key_exists($annotationType, $annotationsMap)) {
            // Set a new class based on the match found
            $annotation = new $annotationsMap[$annotationType]();
        } else {
            // Otherwise set a new base annotation model
            $annotation = new AnnotationModel();
        }

        return $annotation;
    }

    /**
     * Get annotation matches.
     *
     * @param string $docString The doc string
     *
     * @return array[]
     */
    protected function getMatches(string $docString): ?array
    {
        preg_match_all($this->getRegex(), $docString, $matches);

        return $matches ?? null;
    }

    /**
     * Set a matched annotation.
     *
     * @param array $matches        The matches
     *                              [
     *                              0 => matches,
     *                              1 => annotation,
     *                              2 => type,
     *                              3 => args,
     *                              4 => var,
     *                              5 => desc
     *                              ]
     * @param int   $index          The index
     * @param array $annotations    The annotations list
     *
     * @throws InvalidAnnotationKeyArgument
     *
     * @return void
     */
    protected function setAnnotation(array $matches, int $index, array &$annotations): void
    {
        $parts = $this->getParts($matches, $index);

        // Get the annotation model from the annotations map
        $annotation = $this->getAnnotationFromMap($parts['annotation']);

        // Set the annotation's type
        $annotation->setAnnotationType($parts['annotation']);

        // If there are properties
        if (null !== $parts['properties'] && $parts['properties']) {
            // Filter the properties and set them in the annotation
            $annotation->setAnnotationProperties($this->getPropertiesAsArray($parts['properties']));

            // Having set the properties there's no need to retain this key in
            // the properties
            unset($parts['properties']);

            // Set the annotation's properties to setters if they exist
            $this->setProperties($annotation);
        }

        // Set all the matches
        $annotation->setMatches($parts);

        // Set the annotation in the list
        $annotations[] = $annotation;
    }

    /**
     * Set the annotation's properties.
     *
     * @param Annotation $annotation The annotation
     *
     * @return void
     */
    protected function setProperties(Annotation $annotation): void
    {
        $properties = $annotation->getAnnotationProperties();

        // Iterate through the properties
        foreach ($annotation->getAnnotationProperties() as $key => $argument) {
            $methodName = 'set' . ucfirst($key);

            // Check if there is a setter function for this argument
            if (method_exists($annotation, $methodName)) {
                // Set the argument using the setter
                $annotation->{$methodName}($argument);

                // Unset from the properties array
                unset($properties[$key]);
            }
        }

        $annotation->setAnnotationProperties($properties);
    }

    /**
     * Get the properties for a matched annotation.
     *
     * @param array $matches The matches
     * @param int   $index   The index
     *
     * @return array
     */
    protected function getParts(array $matches, int $index): array
    {
        $properties = [];

        // Written like this to appease the code coverage gods
        $properties['annotation']  = $matches[1][$index] ?? null;
        $properties['properties']  = $matches[2][$index] ?? null;
        $properties['type']        = $matches[3][$index] ?? null;
        $properties['variable']    = $matches[4][$index] ?? null;
        $properties['description'] = $matches[5][$index] ?? null;

        return $this->processParts($properties);
    }

    /**
     * Process the properties.
     *
     * @param array $parts The properties
     *
     * @return array
     */
    protected function processParts(array $parts): array
    {
        // If the type and description exist by the variable does not
        // then that means the variable regex group captured the
        // first word of the description
        if ($parts['type'] && $parts['description'] && ! $parts['variable']) {
            // Rectify this by concatenating the type and description
            $parts['description'] = $parts['type'] . $parts['description'];

            // Then unset the type
            unset($parts['type']);
        }

        // Iterate through the properties
        foreach ($parts as &$property) {
            // Clean each one
            $property = $this->cleanMatch($property);
        }

        return $parts;
    }

    /**
     * Determine if a value is a defined constant.
     *
     * @param mixed $value The value to check
     *
     * @return mixed
     */
    protected function determinePropertyValue($value)
    {
        if (is_array($value)) {
            foreach ($value as &$item) {
                $item = $this->determinePropertyValue($item);
            }

            unset($item);

            return $value;
        }

        // Trim the value of spaces
        $value = trim($value);

        // If there was no double colon found there's no need to go further
        if (strpos($value, '::') === false) {
            return $value;
        }

        // Determine if the value is a constant
        if (defined($value)) {
            // Set the value as the constant's value
            return constant($value);
        }

        [$class, $member] = explode('::', $value, 2);

        // Check for static property
        if (property_exists($class, $member)) {
            return $class::$$member;
        }

        // Check for static method
        if (method_exists($class, $member)) {
            return $class::$member();
        }

        return $value;
    }

    /**
     * Clean a match from asterisks and new lines.
     *
     * @param string $match The match
     *
     * @return string
     */
    protected function cleanMatch(string $match = null): ?string
    {
        if (! $match) {
            return $match;
        }

        return trim(str_replace('*', '', $match));
    }

    /**
     * The items provided by this provider.
     *
     * @return array
     */
    public static function provides(): array
    {
        return [
            AnnotationsParserContract::class,
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
        $app->container()->singleton(
            AnnotationsParserContract::class,
            new static(
                $app
            )
        );
    }
}