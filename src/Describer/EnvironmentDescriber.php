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

use Cvc\TwigDocumentor\Description\DescriptionCollection;
use Cvc\TwigDocumentor\Documentation;
use Twig\Environment;

class EnvironmentDescriber
{
    private $functionDescriber;
    private $filterDescriber;

    public function __construct(FunctionDescriber $functionDescriber, FilterDescriber $filterDescriber)
    {
        $this->functionDescriber = $functionDescriber;
        $this->filterDescriber = $filterDescriber;
    }

    public function describe(Environment $twig): Documentation
    {
        $functionDescriptions = new DescriptionCollection();
        /** @psalm-suppress InternalMethod This package must access the functions to output a description. */
        foreach ($twig->getFunctions() as $function) {
            $functionDescriptions = $functionDescriptions->with($this->functionDescriber->describe($function));
        }

        $filterDescriptions = new DescriptionCollection();
        /** @psalm-suppress InternalMethod This package must access the filters to output a description. */
        foreach ($twig->getFilters() as $filter) {
            $filterDescriptions = $filterDescriptions->with($this->filterDescriber->describe($filter));
        }

        return new Documentation($functionDescriptions, $filterDescriptions);
    }
}
