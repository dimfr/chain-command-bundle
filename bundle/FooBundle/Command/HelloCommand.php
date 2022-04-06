<?php

namespace Dimfr\Bundle\FooBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HelloCommand
 * @package Dimfr\Bundle\FooBundle\Command
 */
class HelloCommand extends Command
{
    /**
     * Configures the current command.
     *
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('foo:hello');
    }

    /**
     * Executes the current command.
     *
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello from Foo!');

        return self::SUCCESS;
    }

}