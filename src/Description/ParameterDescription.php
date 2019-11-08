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

final class ParameterDescription
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
     * @var ?string
     */
    private $type;

    /**
     * @var ?string
     */
    private $defaultValue;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

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

    public function getType()
    {
        return $this->type;
    }

    public function withType(string $type): self
    {
        $new = clone $this;
        $new->type = $type;

        return $new;
    }

    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    public function withDefaultValue(string $defaultValue): self
    {
        $new = clone $this;
        $new->defaultValue = $defaultValue;

        return $new;
    }

    public function hasDefaultValue(): bool
    {
        return $this->defaultValue != null;
    }
}
