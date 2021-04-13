<?php

/*
 * This file is part of the cvc/twig-documentor package.
 *
 * (c) CARL von CHIARI GmbH <opensource@cvc.digital>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cvc\TwigDocumentor\Description;

abstract class Description
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string|null
     */
    private $descriptionText;

    /**
     * @var CodeExample[]
     */
    private $examples = [];

    /**
     * @var ParameterDescription[]
     */
    private $parameters = [];

    /**
     * Path to the file where the function or filter was defined or implemented.
     *
     * @var string|null
     */
    private $source;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Renders an example which shows all attributes and the corresponding defaults.
     */
    abstract public function getDefaultExample(): string;

    public function getName(): string
    {
        return $this->name;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function withSummary(string $summary): self
    {
        $new = clone $this;
        $new->summary = $summary;

        return $new;
    }

    public function getDescriptionText(): string
    {
        return (string) $this->descriptionText;
    }

    public function hasDescriptionText(): bool
    {
        return $this->descriptionText != null;
    }

    public function withDescriptionText(string $descriptionText): self
    {
        $new = clone $this;
        $new->descriptionText = $descriptionText;

        return $new;
    }

    public function getExamples(): array
    {
        return $this->examples;
    }

    public function withExample(CodeExample $example): self
    {
        $new = clone $this;
        $new->examples[] = $example;

        return $new;
    }

    /**
     * @return ParameterDescription[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function withParameter(ParameterDescription $parameter): self
    {
        $new = clone $this;
        $new->parameters[] = $parameter;

        return $new;
    }

    public function getSource(): string
    {
        return (string) $this->source;
    }

    public function hasSource(): bool
    {
        return $this->source !== null;
    }

    public function withSource(string $source): self
    {
        $new = clone $this;
        $new->source = $source;

        return $new;
    }

    /**
     * @param int                          $pos        The position, where the function arguments begin. Needed to generate line breaks.
     * @param array|ParameterDescription[] $parameters
     */
    protected function renderFunctionParameters(int $pos, array $parameters): string
    {
        if (count($parameters) === 0) {
            return '()';
        }

        $parameters = array_map(function (ParameterDescription $parameter): string {
            if ($parameter->hasDefaultValue()) {
                return "{$parameter->getName()} = {$parameter->getDefaultValue()}";
            }

            return $parameter->getName();
        }, $parameters);

        $parameterOutput = '('.implode(', ', $parameters).')';

        if (count($parameters) === 1 || ($pos + mb_strlen($parameterOutput)) < 76) {
            return $parameterOutput;
        }

        // parameters are too long: show each parameter in a row
        $parameters = array_map(function (string $parameterOutput): string {
            return str_pad('', 4).$parameterOutput;
        }, $parameters);

        return "(\n".implode(",\n", $parameters)."\n)";
    }
}
