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

final class CodeExample
{
    private $sourceCode;

    public function __construct(string $sourceCode)
    {
        $this->sourceCode = $sourceCode;
    }

    public function getSourceCode(): string
    {
        return $this->sourceCode;
    }
}
