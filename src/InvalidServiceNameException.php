<?php

declare(strict_types=1);

namespace Mauricek\FormFactory;

use Psr\Container\ContainerExceptionInterface;

use RuntimeException;
use function sprintf;

class InvalidServiceNameException extends RuntimeException implements ContainerExceptionInterface
{
    public static function forService($serviceName) : self
    {
         return new self(sprintf(
            'Only services beginning with "form-" may be constructed via the %s; received %s',
            ConfigFactory::class,
            $serviceName
        ));
    }
}
