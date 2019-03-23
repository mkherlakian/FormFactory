<?php

declare(strict_types=1);

namespace Mauricek\FormFactory;

use Psr\Container\ContainerExceptionInterface;

use RuntimeException;
use function implode;
use function sprintf;

class ConfigKeyNotFoundException extends RuntimeException implements ContainerExceptionInterface
{
    public static function forForm(string $formKey) : self
    {
        return new self("Unable to find key $formKey under forms.");
    }
}
