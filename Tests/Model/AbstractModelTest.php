<?php

namespace Elective\FormatterBundle\Tests\Model;

use Elective\FormatterBundle\Model\AbstractModel;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Elective\FormatterBundle\Tests\Model\AbstractModel
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class AbstractModelTest extends TestCase
{
    public function testConstructor()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $model = $this->getMockBuilder(AbstractModel::class)
            ->setConstructorArgs([$manager, $dispatcher, $requestStack])
            ->getMock();

        $this->assertInstanceOf(EntityManagerInterface::class, $model->getManager());
        $this->assertInstanceOf(EventDispatcherInterface::class, $model->getDispatcher());
        $this->assertInstanceOf(RequestStack::class, $model->getRequestStack());
    }

    public function testManager()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $model = new A($manager, $dispatcher, $requestStack);
        
        $newManager = $this->createMock(EntityManagerInterface::class);
        $this->assertInstanceOf(AbstractModel::class, $model->setManager($newManager));
        $this->assertSame($newManager, $model->getManager());
    }

    public function testDispatcher()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $model = new A($manager, $dispatcher, $requestStack);
        
        $newDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->assertInstanceOf(AbstractModel::class, $model->setDispatcher($newDispatcher));
        $this->assertSame($newDispatcher, $model->getDispatcher());
    }

    public function testRequestStack()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $model = new A($manager, $dispatcher, $requestStack);
        
        $newRequestStack = $this->createMock(RequestStack::class);
        $this->assertInstanceOf(AbstractModel::class, $model->setRequestStack($newRequestStack));
        $this->assertSame($newRequestStack, $model->getRequestStack());
    }
}

class A extends AbstractModel{
    public function getName(): string {
        return 'a';
    }
}
