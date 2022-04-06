<?php

namespace Dimfr\Bundle\ChainCommandBundle\EventListener;

use Dimfr\Bundle\ChainCommandBundle\Chain\ChainProviderInterface;
use Dimfr\Bundle\ChainCommandBundle\Exception\NotMasterCommandException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleEvent;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;


/**
 * Class ConsoleListener
 * @package Dimfr\Bundle\ChainCommandBundle\EventListener
 */
class ConsoleListener
{
    /**
     * Chain provider
     *
     * @var ChainProviderInterface
     */
    private ChainProviderInterface $chainProvider;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * Constructor
     *
     * @param ChainProviderInterface $chainProvider
     * @param LoggerInterface $logger
     */
    public function __construct(ChainProviderInterface $chainProvider, LoggerInterface $logger)
    {
        $this->chainProvider = $chainProvider;
        $this->logger = $logger;
    }

    /**
     * Callback triggered before executing console command
     *
     * @param ConsoleEvent $event
     * @throws NotMasterCommandException
     */
    public function onConsoleCommand(ConsoleEvent $event)
    {
        $command = $event->getCommand();

        if ($this->chainProvider->isChainMember($command)) {
            $event->stopPropagation();

            throw new NotMasterCommandException($command->getName(), $this->chainProvider->getMasterAlias($command));
        }

        if ($this->chainProvider->isChainMaster($command)) {
            $this->logChainCommands($command);

            $this->logger->info($this->runCommand($command));
        }
    }

    /**
     * Callback triggered before terminating console command
     *
     * @param ConsoleEvent $event
     * @throws \Exception
     */
    public function onConsoleTerminate(ConsoleEvent $event)
    {
        $command = $event->getCommand();

        if ($commands = $this->chainProvider->getChainCommands($command)) {
            $this->logger->info(sprintf(
                'Executing %s chain members:',
                $command->getName()
            ));

            /** @var Command $chainCommand */
            foreach ($commands as $chainCommand) {
                $output = $this->runCommand($chainCommand);

                $this->logger->info($output);
                echo $output;
            }

            $this->logger->info(sprintf(
                'Execution of %s chain completed.',
                $command->getName()
            ));
        }
    }

    /**
     * Log chain commands
     *
     * @param Command $command
     */
    protected function logChainCommands(Command $command)
    {
        $this->logger->info(sprintf(
            '%s is a master command of a command chain that has registered member commands',
            $command->getName()
        ));

        $chainCommandsAliases = $this->chainProvider->getChainCommandsAliases($command);

        $this->logger->info(sprintf(
            '%s registered as a member of %s command chain',
            implode(', ', $chainCommandsAliases), $command->getName()
        ));

        $this->logger->info(sprintf(
            'Executing %s command itself first:',
            $command->getName(),
        ));
    }

    /**
     * Run console command and return output
     *
     * @param Command $command
     * @return string
     * @throws \Exception
     */
    protected function runCommand(Command $command): string
    {
        $input = new ArrayInput(['command' => $command->getName()]);
        $output = new BufferedOutput();

        $command->run($input, $output);

        return $output->fetch();
    }
}