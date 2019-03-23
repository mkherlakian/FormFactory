<?php

declare(strict_types=1);

namespace Mauricek\FormFactory;

use Zend\Form\Factory as ZendFormFactory;
use Zend\Form\Form as ZendForm;
use Psr\Container\ContainerInterface;

use function preg_match;

trait BuildFormTrait {
    protected $formFactory;

    public function setFormFactory(ZendFormFactory $factory)
    {
        $this->formFactory = $factory;
    }

    public function getFormFactory() : ZendFormFactory
    {
        if(is_null($this->formFactory)) {
            $this->formFactory = new ZendFormFactory();
        }
        return $this->formFactory;
    }

    public function buildForm(ContainerInterface $container, string $serviceName) : ZendForm
    {
        if (! preg_match('/^form-/i', $serviceName)) {
            throw InvalidServiceNameException::forService($serviceName);
        }

        if(! isset($container->get('config')['forms']) || count($container->get('config')['forms']) == 0) {
            throw FormsNotDefinedException::for('forms');
        }

        $formKey = substr($serviceName, 5);
        if(!isset($container->get('config')['forms'][$formKey])) {
            throw ConfigKeyNotFoundException::forForm($formKey);
        }

        $formConf = $container->get('config')['forms'][$formKey];

        return $this
            ->getFormFactory()
            ->createForm($formConf);
    }
}
