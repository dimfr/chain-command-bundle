<?php

namespace Dimfr\Bundle\ChainCommandBundle\Chain;

use Symfony\Component\Console\Command\Command;

/**
 * Interface ChainProviderInterface
 * @package Dimfr\Bundle\ChainCommandBundle\Chain
 */
interface ChainProviderInterface
{
    /**
     * Adds chain member during processing 'chain.command' tagged services
     *
     * @param string $className
     * @param array $tag
     */
    public function addChainMember(string $className, array $tag): void;

    /**
     * Get next commands in chain
     *
     * @param Command $command
     * @return Command[]|[]
     */
    public function getChainCommands(Command $command): array;

    /**
     * Get next commands aliases in chain
     * @param Command $command
     * @return array
     */
    public function getChainCommandsAliases(Command $command): array;

    /**
     * Is command member of a chain
     *
     * @param Command $command
     * @return bool
     */
    public function isChainMember(Command $command): bool;

    /**
     * Is command master of a chain
     *
     * @param Command $command
     * @return bool
     */
    public function isChainMaster(Command $command): bool;

    /**
     * Get master command alias of chain member
     *
     * @param Command $command
     * @return string|null
     */
    public function getMasterAlias(Command $command): ?string;
}