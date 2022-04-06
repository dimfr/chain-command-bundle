<?php

namespace Dimfr\Bundle\ChainCommandBundle\Tests\Unit\Chain;

use Dimfr\Bundle\ChainCommandBundle\Chain\ChainProvider;
use Dimfr\Bundle\ChainCommandBundle\Tests\Unit\MockFactoryTrait;
use Monolog\Test\TestCase;
use Symfony\Component\Console\Command\Command;

/**
 * Class ChainProviderTest
 * @package Dimfr\Bundle\ChainCommandBundle\Tests\Unit\Chain
 */
class ChainProviderTest extends TestCase
{
    use MockFactoryTrait;

    /**
     * Test adding chain members
     *
     * @throws \Dimfr\Bundle\ChainCommandBundle\Exception\ChainCommandTagException
     */
    public function testAddChainMember()
    {
        [$masterCommand, $invalidMasterCommand, $chainMembers] = $this->getChainsTestData();

        $chainProvider = new ChainProvider();

        foreach ($chainMembers as $member) {
            $chainProvider->addChainMember(... $member);
        }

        $this->assertTrue($chainProvider->isChainMaster($this->createMasterCommand($masterCommand)));
        $this->assertFalse($chainProvider->isChainMaster($this->createMasterCommand($invalidMasterCommand)));

        $this->assertTrue($chainProvider->isChainMember($this->createCommandMock($chainMembers[0][0])));
        $this->assertFalse($chainProvider->isChainMember($this->createCommandMock($invalidMasterCommand)));
    }

    /**
     * Chains test data
     * @return array
     */
    protected function getChainsTestData(): array
    {
        $masterCommand = 'BarCommand';
        $invalidMasterCommand = 'InvalidMasterCommand';

        return [
            $masterCommand,
            $invalidMasterCommand,
            [
                ['FooCommand', ['master' => $masterCommand, 'position' => 1]],
                ['BazCommand', ['master' => $masterCommand, 'position' => 2]],
            ]
        ];
    }

    /**
     * Create command class object with passed name
     *
     * @param string $commandName
     * @return Command
     */
    protected function createMasterCommand(string $commandName): Command
    {
        return new class($commandName) extends Command {};
    }
}