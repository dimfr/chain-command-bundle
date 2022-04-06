<?php

namespace Dimfr\Bundle\ChainCommandBundle\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;

/**
 * Trait MockFactoryTrait
 * @package Dimfr\Bundle\ChainCommandBundle\Tests\Unit
 */
trait MockFactoryTrait
{
    /**
     * Creates mock object from class name
     *
     * @param string $className
     * @param string|null $mockClassName
     * @return MockObject
     */
    public function createMockObject(string $className, string $mockClassName = null): MockObject
    {
        $builder = $this->getMockBuilder($className)
            ->disableOriginalConstructor();

        if ($mockClassName) {
            $builder->setMockClassName($mockClassName);
        }

        return $builder->getMock();
    }


    /**
     * Create command class mock
     *
     * @param string|null $commandClass
     * @return MockObject
     */
    protected function createCommandMock(string $commandClass = null): MockObject
    {
        return $this->createMockObject(Command::class, $commandClass);
    }

    /**
     * Create console event class mock
     *
     * @return MockObject
     */
    protected function createConsoleEventMock(): MockObject
    {
        return $this->createMockObject(ConsoleEvent::class);
    }

}