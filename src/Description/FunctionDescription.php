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

final class FunctionDescription extends Description
{
    public function getDefaultExample(): string
    {
        $example = "{{ {$this->getName()}";
        $example .= "{$this->renderFunctionParameters(mb_strlen($example), $this->getParameters())} }}";

        return $example;
    }
}
