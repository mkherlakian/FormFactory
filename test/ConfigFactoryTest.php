<?php

declare(strict_types=1);

use Mauricek\FormFactory\ConfigFactory;
use Mauricek\FormFactory\ConfigKeyNotFoundException;
use Mauricek\FormFactory\FormsNotDefinedException;
use Mauricek\FormFactory\InvalidServiceNameException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Form\Factory as ZendFormFactory;
use Zend\Form\Form as ZendForm;

class ConfigFactoryTest extends TestCase {
    protected $container;
    protected $factory;
    protected $zendFormFactory;

    public function setUp()
    {
        $this->container        = $this->prophesize(ContainerInterface::class);
        $this->factory          = new ConfigFactory();
        $this->zendFormFactory  = $this->prophesize(ZendFormFactory::class);
    }

    public function testRaisesExceptionForInvalidServiceName()
    {
        $this->container->get('config')->shouldNotBeCalled();
        $this->expectException(InvalidServiceNameException::class);
        ($this->factory)($this->container->reveal(), 'invalid-name');
    }

    public function testRaisesExceptionForFormsNotDefined()
    {
        $this->container->get('config')->willReturn([]);
        $this->expectException(FormsNotDefinedException::class);
        ($this->factory)($this->container->reveal(), 'form-whatever');
    }

    public function testRaisesExceptionForKeyNotFound()
    {
        $this->container->get('config')->willReturn(['forms' => [
            'space::retrieveuser'   => [],
            'space::createuser'     => []
        ]]);
        $this->expectException(ConfigKeyNotFoundException::class);
        ($this->factory)($this->container->reveal(), 'form-space::notdefined');
    }

    public function testValidFormGetsBuilt()
    {
        $this->container->get('config')->willReturn(['forms' => [
            'space::retrieveuser'   => ['key' => 'value'],
            'space::createuser'     => []
        ]]);

        $this
            ->zendFormFactory
            ->createForm(['key' => 'value'])
            ->willReturn($this->prophesize(ZendForm::class)->reveal())
            ->shouldBeCalledTimes(1);

        $this->factory->setFormFactory($this->zendFormFactory->reveal());

        ($this->factory)($this->container->reveal(), 'form-space::retrieveuser');
    }
}
