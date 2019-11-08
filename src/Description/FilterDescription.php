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

final class FilterDescription extends Description
{
    public function getDefaultExample(): string
    {
        $parameters = $this->getParameters();
        $firstParameter = array_shift($parameters);
        assert($firstParameter instanceof ParameterDescription);

        $example = "{{ {$firstParameter->getName()} | {$this->getName()}";
        $example .= "{$this->renderFunctionParameters(mb_strlen($example), $parameters)} }}";

        return $example;
    }

    protected function renderFunctionParameters(int $pos, array $parameters): string
    {
        if (count($parameters) === 0) {
            return '';
        }

        return parent::renderFunctionParameters($pos, $parameters);
    }
}
