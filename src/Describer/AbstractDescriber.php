<?php

/*
 * This file is part of the cvc/twig-documentor package.
 *
 * (c) CARL von CHIARI GmbH <opensource@cvc.digital>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cvc\TwigDocumentor\Describer;

use Cvc\TwigDocumentor\Description\CodeExample;
use Cvc\TwigDocumentor\Description\Description;
use Cvc\TwigDocumentor\Description\ParameterDescription;
use phpDocumentor\Reflection\DocBlock;
use phpDocumentor\Reflection\DocBlock\Tags;
use phpDocumentor\Reflection\DocBlockFactoryInterface;
use ReflectionClass;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;

abstract class AbstractDescriber
{
    private $docBlockFactory;

    public function __construct(DocBlockFactoryInterface $docBlockFactory)
    {
        $this->docBlockFactory = $docBlockFactory;
    }

    protected function describeCallable(Description $description, $callable, int $skipParameters): Description
    {
        if (is_array($callable)) {
            if (is_object($callable[0])) {
                $description = $this->describeClassMethod($description, get_class($callable[0]), $callable[1], $skipParameters);
            } elseif (is_string($callable[0])) {
                $description = $this->describeClassMethod($description, $callable[0], $callable[1], $skipParameters);
            }
        } elseif (is_string($callable)) {
            if (function_exists($callable)) {
                $description = $this->describeFunction($description, $callable, $skipParameters);
            }
        }

        return $description;
    }

    private function describeClassMethod(Description $description, string $class, string $method, int $skipParameters): Description
    {
        if (!class_exists($class)) {
            return $description;
        }

        $reflectionClass = new ReflectionClass($class);
        $reflectionMethod = $reflectionClass->getMethod($method);
        $description = $description->withSource($reflectionClass->getFileName());

        $docComment = $reflectionMethod->getDocComment();
        $docBlock = null;

        if ($docComment) {
            $docBlock = $this->docBlockFactory->create($docComment);
            $description = $this->describeDocBlock($description, $docBlock);

            foreach ($docBlock->getTagsByName('example') as $example) {
                assert($example instanceof Tags\Generic);
                $description = $description->withExample(new CodeExample($example->__toString()));
            }
        }

        $reflectionParameters = $reflectionMethod->getParameters();
        $description = $this->describeParameters($description, $docBlock, $reflectionParameters, $skipParameters);

        return $description;
    }

    private function describeFunction(Description $description, string $function, int $skipParameters): Description
    {
        if (!function_exists($function)) {
            return $description;
        }

        $reflectionFunction = new ReflectionFunction($function);
        $docComment = $reflectionFunction->getDocComment();
        $description = $description->withSource($reflectionFunction->getFileName());

        $docBlock = null;
        if ($docComment) {
            $docBlock = $this->docBlockFactory->create($docComment);
            $description = $this->describeDocBlock($description, $docBlock);
        }

        $description = $this->describeParameters($description, $docBlock, $reflectionFunction->getParameters(), $skipParameters);

        return $description;
    }

    private function describeDocBlock(Description $description, DocBlock $docBlock): Description
    {
        $description = $description->withSummary($docBlock->getSummary());

        $descriptionText = (string) $docBlock->getDescription();
        if ($descriptionText) {
            $description = $description->withDescriptionText($descriptionText);
        }

        return $description;
    }

    /**
     * @param ReflectionParameter[] $reflectionParameters
     */
    private function describeParameters(Description $description, ?DocBlock $docBlock, array $reflectionParameters, int $skipParameters): Description
    {
        if (count($reflectionParameters) <= $skipParameters) {
            return $description;
        }

        $reflectionParameters = array_slice($reflectionParameters, $skipParameters);

        /** @var Tags\Param[] $paramTags */
        $paramTags = [];
        if ($docBlock) {
            foreach ($docBlock->getTagsByName('param') as $tag) {
                assert($tag instanceof Tags\Param);
                $variableName = $tag->getVariableName();
                if ($variableName !== null) {
                    $paramTags[$variableName] = $tag;
                }
            }
        }

        foreach ($reflectionParameters as $parameter) {
            $parameterDescription = new ParameterDescription($parameter->getName());

            if ($parameter->hasType()) {
                $type = $parameter->getType();

                /**
                 * @psalm-suppress TypeDoesNotContainType
                 * @psalm-suppress UndefinedClass
                 */
                if ($type instanceof \ReflectionUnionType) {
                    $typeName = implode(
                        '|',
                        array_map(
                            static function (ReflectionNamedType $namedType) { return $namedType->getName(); },
                            $type->getTypes()
                        )
                    );
                } else {
                    assert($type instanceof ReflectionNamedType);
                    $typeName = $type->getName();
                }

                $parameterDescription = $parameterDescription->withType($typeName);
            }

            if ($parameter->isDefaultValueAvailable()) {
                if ($parameter->isDefaultValueConstant()) {
                    $parameterDescription = $parameterDescription->withDefaultValue((string) $parameter->getDefaultValueConstantName());
                } else {
                    $parameterDescription = $parameterDescription->withDefaultValue($this->formatDefaultValue($parameter->getDefaultValue()));
                }
            }

            if (isset($paramTags[$parameter->getName()])) {
                $tag = $paramTags[$parameter->getName()];
                $tagDescription = $tag->getDescription();
                if ($tagDescription !== null) {
                    $parameterDescription = $parameterDescription->withSummary($tagDescription->__toString());
                }
            }

            $description = $description->withParameter($parameterDescription);
        }

        return $description;
    }

    private function formatDefaultValue($defaultValue): string
    {
        if ($defaultValue === null) {
            return 'null';
        }

        if ($defaultValue === true) {
            return 'true';
        }

        if ($defaultValue === false) {
            return 'false';
        }

        $formattedValue = str_replace("\n", '', var_export($defaultValue, true));

        if (is_array($defaultValue)) {
            return '['.mb_substr($formattedValue, 7, -1).']';
        }

        return $formattedValue;
    }
}
