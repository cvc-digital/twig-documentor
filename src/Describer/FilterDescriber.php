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

use Cvc\TwigDocumentor\Description\FilterDescription;
use Twig\TwigFilter;

final class FilterDescriber extends AbstractDescriber
{
    public function describe(TwigFilter $filter): FilterDescription
    {
        $numberOfSkippedParameters = intval($filter->needsContext()) + intval($filter->needsEnvironment());
        $description = $this->describeCallable(new FilterDescription($filter->getName()), $filter->getCallable(), $numberOfSkippedParameters);
        assert($description instanceof FilterDescription);

        return $description;
    }
}
