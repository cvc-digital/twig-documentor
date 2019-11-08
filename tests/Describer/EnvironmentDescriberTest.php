<?php

/*
 * This file is part of the cvc/twig-documentor package.
 *
 * (c) CARL von CHIARI GmbH <opensource@cvc.digital>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cvc\TwigDocumentor\Tests\Describer;

use Cvc\TwigDocumentor\Describer\EnvironmentDescriber;
use Cvc\TwigDocumentor\Describer\FilterDescriber;
use Cvc\TwigDocumentor\Describer\FunctionDescriber;
use phpDocumentor\Reflection\DocBlockFactory;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Loader\ArrayLoader;
use Twig\TwigFilter;
use Twig\TwigFunction;

class EnvironmentDescriberTest extends TestCase
{
    public function testDescribeEnvironment()
    {
        $environment = new Environment(new ArrayLoader());
        $environment->addExtension(new class() extends AbstractExtension {
            public function getFilters()
            {
                return [
                    new TwigFilter('example_filter', [$this, 'exampleFilter']),
                ];
            }

            public function getFunctions()
            {
                return [
                    new TwigFunction('example_function', [$this, 'exampleFunction'], ['needs_environment' => true]),
                ];
            }

            /**
             * This is an example filter.
             *
             * @param string $string            this is an example parameter
             * @param string $someOtherVariable this is a second example parameter
             *
             * @return string
             */
            public function exampleFilter(string $string, string $someOtherVariable)
            {
                return $string;
            }

            /**
             * This is an example function.
             *
             * @return string
             */
            public function exampleFunction(Environment $environment, string $input, string $anotherArgument)
            {
                return $input;
            }
        });

        $environmentDescriber = new EnvironmentDescriber(
            new FunctionDescriber(DocBlockFactory::createInstance()),
            new FilterDescriber(DocBlockFactory::createInstance())
        );
        $documentation = $environmentDescriber->describe($environment);
        $documentation = $documentation->withSource(__DIR__.'/*');

        $this->assertCount(1, $documentation->getFunctions());
        $this->assertCount(1, $documentation->getFilters());
    }
}
