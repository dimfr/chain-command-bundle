<?php

namespace Dimfr\Bundle\BarBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class HiCommand
 * @package Dimfr\Bundle\BarBundle\Command
 */
class HiCommand extends Command
{
    /**
     * Configures the current command.
     *
     * {@inheritdoc}
     */
    public function configure()
    {
        $this->setName('bar:hi');
    }

    /**
     * Executes the current command.
     *
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hi from Bar!');

        return self::SUCCESS;
    }


}