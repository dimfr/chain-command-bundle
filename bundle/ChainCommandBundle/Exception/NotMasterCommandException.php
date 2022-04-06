<?php

namespace Dimfr\Bundle\ChainCommandBundle\Exception;

/**
 * Class NotMasterCommandException
 * @package Dimfr\Bundle\ChainCommandBundle\Exception
 */
class NotMasterCommandException extends \Exception
{
    /**
     * Constructor
     *
     * @param string $commandName
     * @param string|null $masterCommandName
     */
    public function __construct(string $commandName, string $masterCommandName = null)
    {
        parent::__construct(sprintf(
            'Error: %s command is a member of %s command chain and cannot be executed on its own.',
            $commandName, $masterCommandName
        ));
    }
}