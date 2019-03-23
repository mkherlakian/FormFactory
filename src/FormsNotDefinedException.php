<?php

declare(strict_types=1);

namespace Mauricek\FormFactory;

use Psr\Container\ContainerExceptionInterface;

use RuntimeException;
use function implode;
use function sprintf;

class FormsNotDefinedException extends RuntimeException implements ContainerExceptionInterface
{
    public static function for($formKey) : self
    {
        return new self("Forms configuration not found (Expected under key $formKey)");
    }
}
