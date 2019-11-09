# Twig Documentor

[![Build Status](https://travis-ci.org/cvc-digital/twig-documentor.svg?branch=master)](https://travis-ci.org/cvc-digital/twig-documentor)
[![codecov](https://codecov.io/gh/cvc-digital/twig-documentor/branch/master/graph/badge.svg)](https://codecov.io/gh/cvc-digital/twig-documentor)
[![Packagist](https://img.shields.io/packagist/v/cvc/twig-documentor.svg)](https://packagist.org/packages/cvc/twig-documentor)
[![Psalm coverage](https://shepherd.dev/github/cvc-digital/twig-documentor/coverage.svg?)](https://shepherd.dev/github/cvc-digital/twig-documentor)
[![GitHub license](https://img.shields.io/github/license/cvc-digital/twig-documentor.svg)](https://github.com/cvc-digital/twig-documentor/blob/master/LICENSE)

Automatically generates documentation for Twig extensions.

## Installation

```
composer require --dev cvc/twig-documentor
```

## Usage

```php
<?php

$environment = new \Twig\Environment(new \Twig\Loader\ArrayLoader());
$environmentDescriber = new \Cvc\TwigDocumentor\Describer\EnvironmentDescriber(
    new \Cvc\TwigDocumentor\Describer\FunctionDescriber(\phpDocumentor\Reflection\DocBlockFactory::createInstance()),
    new \Cvc\TwigDocumentor\Describer\FilterDescriber(\phpDocumentor\Reflection\DocBlockFactory::createInstance())
);
$documentation = $environmentDescriber->describe($environment);
$documentation = $documentation->withSource(__DIR__.'/*');
```

## Development Team

<table>
    <tr>
        <td align="center" valign="top">
            <img width="150" height="150" src="https://github.com/markuspoerschke.png?s=150">
            <br>
            <a href="https://github.com/markuspoerschke">Markus Poerschke</a>
            <p>Developer</p>
        </td>
    </tr>
</table>
