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

use Cvc\TwigDocumentor\Description\FunctionDescription;
use Twig\TwigFunction;

final class FunctionDescriber extends AbstractDescriber
{
    public function describe(TwigFunction $function): FunctionDescription
    {
        $numberOfSkippedParameters = intval($function->needsContext()) + intval($function->needsEnvironment());
        $description = $this->describeCallable(new FunctionDescription($function->getName()), $function->getCallable(), $numberOfSkippedParameters);
        assert($description instanceof FunctionDescription);

        return $description;
    }
}
