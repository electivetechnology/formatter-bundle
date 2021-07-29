<?php

namespace Elective\FormatterBundle\Tests\Model;

use Elective\FormatterBundle\Model\AbstractModel;
use Elective\FormatterBundle\Entity\IdableInterface;
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Elective\FormatterBundle\Tests\Model\AbstractModel
 *
 * @author Kris Rybak <kris@electivegroup.com>
 */
class AbstractModelTest extends TestCase
{
    protected $model;

    protected function setUp(): void
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(LoggerInterface::class);

        $this->model = new A($manager, $dispatcher, $requestStack, $logger);
    }

    public function testConstructor()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(LoggerInterface::class);

        $model = $this->getMockBuilder(AbstractModel::class)
            ->setConstructorArgs([$manager, $dispatcher, $requestStack, $logger])
            ->getMock();

        $this->assertInstanceOf(EntityManagerInterface::class, $model->getManager());
        $this->assertInstanceOf(EventDispatcherInterface::class, $model->getDispatcher());
        $this->assertInstanceOf(RequestStack::class, $model->getRequestStack());
        $this->assertInstanceOf(LoggerInterface::class, $model->getLogger());
    }

    public function testManager()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(LoggerInterface::class);

        $model = new A($manager, $dispatcher, $requestStack, $logger);

        $newManager = $this->createMock(EntityManagerInterface::class);
        $this->assertInstanceOf(AbstractModel::class, $model->setManager($newManager));
        $this->assertSame($newManager, $model->getManager());
    }

    public function testDispatcher()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(LoggerInterface::class);

        $model = new A($manager, $dispatcher, $requestStack, $logger);

        $newDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->assertInstanceOf(AbstractModel::class, $model->setDispatcher($newDispatcher));
        $this->assertSame($newDispatcher, $model->getDispatcher());
    }

    public function testRequestStack()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(LoggerInterface::class);

        $model = new A($manager, $dispatcher, $requestStack, $logger);

        $newRequestStack = $this->createMock(RequestStack::class);
        $this->assertInstanceOf(AbstractModel::class, $model->setRequestStack($newRequestStack));
        $this->assertSame($newRequestStack, $model->getRequestStack());
    }

    public function testLogger()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $dispatcher = $this->createMock(EventDispatcherInterface::class);
        $requestStack = $this->createMock(RequestStack::class);
        $logger = $this->createMock(LoggerInterface::class);

        $model = new A($manager, $dispatcher, $requestStack, $logger);

        $newLogger = $this->createMock(LoggerInterface::class);
        $this->assertInstanceOf(AbstractModel::class, $model->setLogger($newLogger));
        $this->assertSame($newLogger, $model->getLogger());
    }

    public function tagDataProvider()
    {
        $data = array();
        $item = $this->createMock(IdableInterface::class);
        $item->method('getId')->willReturn('123');

        $data[] = array($item, 'foo123');
        $data[] = array($item, 'foo123');
        $data[] = array(123, 'foo123');
        $data[] = array("123", 'foo123');

        $data[] = array($item, 'foo123');
        $data[] = array(123, 'foo123');

        $data[] = array([], 'foo');
        $data[] = array([1,2,3], 'foo');

        $b = new B();
        $id = "2345abc";
        $b->id = $id;
        $data[] = array($b, 'foo' . $id);

        return $data;
    }

    /**
     * @dataProvider tagDataProvider
     */
    public function testGetTag($item, $expectedTag)
    {
         $this->assertSame($expectedTag, $this->model->getTag($item));
    }
}

class A extends AbstractModel
{
    public static function getName(): string
    {
        return 'foo';
    }
}

class B
{
    public $id;

    public function getId()
    {
        return $this->id;
    }
}
