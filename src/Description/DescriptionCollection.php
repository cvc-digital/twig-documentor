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

use ArrayIterator;
use IteratorAggregate;

final class DescriptionCollection implements IteratorAggregate
{
    /**
     * @var Description[]
     */
    private $descriptions = [];

    public function with(Description $description): self
    {
        $new = clone $this;
        $new->descriptions[$description->getName()] = $description;

        return $new;
    }

    public function withSource(string $pattern): self
    {
        $new = clone $this;
        $new->descriptions = array_filter($new->descriptions, function (Description $description) use ($pattern): bool {
            return $description->hasSource() && fnmatch($pattern, $description->getSource());
        });

        return $new;
    }

    /**
     * @return ArrayIterator<int, Description>
     */
    public function getIterator()
    {
        ksort($this->descriptions);

        return new ArrayIterator($this->descriptions);
    }
}
