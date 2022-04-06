<?php

namespace Dimfr\Bundle\ChainCommandBundle\Tests\Unit\EventListener;

use Dimfr\Bundle\ChainCommandBundle\Chain\ChainProvider;
use Dimfr\Bundle\ChainCommandBundle\EventListener\ConsoleListener;
use Dimfr\Bundle\ChainCommandBundle\Exception\NotMasterCommandException;
use Dimfr\Bundle\ChainCommandBundle\Tests\Unit\MockFactoryTrait;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Class ConsoleListenerTest
 * @package Dimfr\Bundle\ChainCommandBundle\Tests\Unit\EventListener
 */
class ConsoleListenerTest extends TestCase
{
    use MockFactoryTrait;

    /**
     * Console listener for tests
     *
     * @var ConsoleListener
     */
    protected ConsoleListener $consoleListener;

    /**
     * Command chain provider mock
     *
     * @var MockObject
     */
    protected MockObject $chainProvider;

    /**
     * Logger mock
     *
     * @var MockObject
     */
    protected MockObject $logger;

    /**
     * Set up test case
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->chainProvider = $this->createMockObject(ChainProvider::class);
        $this->logger = $this->createMockObject(LoggerInterface::class);

        $this->consoleListener = new ConsoleListener($this->chainProvider, $this->logger);
    }

    /**
     * Test command without chain behavior
     */
    public function testOnConsoleCommandNoChain()
    {
        $command = $this->createCommandMock();
        $consoleEvent = $this->createConsoleEventMock();

        $this->chainProvider->expects($this->once())
            ->method('isChainMember')
            ->willReturn(false);

        $this->chainProvider->expects($this->once())
            ->method('isChainMaster')
            ->willReturn(false);

        $consoleEvent->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $this->logger->expects($this->never())
            ->method('info');

        $this->consoleListener->onConsoleCommand($consoleEvent);
    }

    /**
     * Test empty command
     */
    public function testOnConsoleCommandWithEmptyCommand()
    {
        $consoleEvent = $this->createConsoleEventMock();

        $consoleEvent->expects($this->once())
            ->method('getCommand')
            ->willReturn(null);

        $this->chainProvider->expects($this->never())
            ->method('isChainMember');

        $this->chainProvider->expects($this->never())
            ->method('isChainMaster');

        $this->logger->expects($this->never())
            ->method('info');

        $this->consoleListener->onConsoleCommand($consoleEvent);
    }

    /**
     * Test command chain member behavior throws NotMasterCommandException
     *
     * @throws NotMasterCommandException
     */
    public function testOnConsoleCommandChainMember()
    {
        $command = $this->createCommandMock();
        $consoleEvent = $this->createConsoleEventMock();

        $command->expects($this->once())
            ->method('getName')
            ->willReturn('chain:command')
        ;

        $this->chainProvider->expects($this->once())
            ->method('getMasterAlias')
            ->willReturn('chain:master')
        ;

        $this->chainProvider->expects($this->once())
            ->method('isChainMember')
            ->willReturn(true);

        $this->chainProvider->expects($this->never())
            ->method('isChainMaster')
            ->willReturn(false);

        $consoleEvent->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $this->expectException(NotMasterCommandException::class);

        $this->consoleListener->onConsoleCommand($consoleEvent);
    }

    /**
     * Test chain master behavior
     */
    public function testOnConsoleCommandChainMaster()
    {
        $command = $this->createCommandMock();
        $consoleEvent = $this->createConsoleEventMock();

        $this->chainProvider->expects($this->once())
            ->method('isChainMember')
            ->willReturn(false);

        $this->chainProvider->expects($this->once())
            ->method('isChainMaster')
            ->willReturn(true);

        $consoleEvent->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $this->logger->expects($this->exactly(4))
            ->method('info');

        $this->consoleListener->onConsoleCommand($consoleEvent);
    }

    /**
     * Test command without chain behavior
     */
    public function testOnConsoleTerminateNoChain()
    {
        $command = $this->createCommandMock();
        $consoleEvent = $this->createConsoleEventMock();

        $consoleEvent->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $this->chainProvider->expects($this->once())
            ->method('getChainCommands')
            ->willReturn([])
        ;

        $this->logger->expects($this->never())
            ->method('info');

        $this->consoleListener->onConsoleTerminate($consoleEvent);
    }

    /**
     * Test command with chain behavior
     */
    public function testOnConsoleTerminateChain()
    {
        $command = $this->createCommandMock();
        $consoleEvent = $this->createConsoleEventMock();

        $consoleEvent->expects($this->once())
            ->method('getCommand')
            ->willReturn($command);

        $this->chainProvider->expects($this->once())
            ->method('getChainCommands')
            ->willReturn([$this->createCommandMock()])
        ;

        $this->logger->expects($this->exactly(3))
            ->method('info');

        $this->consoleListener->onConsoleTerminate($consoleEvent);
    }
}