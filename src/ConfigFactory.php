<?php

declare(strict_types=1);

namespace Mauricek\FormFactory;

use Psr\Container\ContainerInterface;

class ConfigFactory {
    use BuildFormTrait;

    public function __invoke(containerInterface $container, string $serviceName = '')
    {
        return $this->buildForm($container, $serviceName);
    }
}
