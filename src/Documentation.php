<?php

/*
 * This file is part of the cvc/twig-documentor package.
 *
 * (c) CARL von CHIARI GmbH <opensource@cvc.digital>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cvc\TwigDocumentor;

use Cvc\TwigDocumentor\Description\DescriptionCollection;

final class Documentation
{
    private $functions;
    private $filters;

    public function __construct(DescriptionCollection $functions, DescriptionCollection $filters)
    {
        $this->functions = $functions;
        $this->filters = $filters;
    }

    public function getFunctions(): DescriptionCollection
    {
        return $this->functions;
    }

    public function getFilters(): DescriptionCollection
    {
        return $this->filters;
    }

    public function withSource(string $pattern): self
    {
        $new = clone $this;
        $new->functions = $new->functions->withSource($pattern);
        $new->filters = $new->filters->withSource($pattern);

        return $new;
    }
}
