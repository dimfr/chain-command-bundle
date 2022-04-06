<?php

namespace Dimfr\Bundle\ChainCommandBundle\Chain;

use Symfony\Component\Console\Command\Command;

/**
 * Class ChainProvider
 * @package Dimfr\Bundle\ChainCommandBundle\Chain
 */
class ChainProvider implements ChainProviderInterface
{
    /**
     * List of command chains
     *
     * @var array
     */
    protected array $chains = [];

    /**
     * Adds chain member during processing 'chain.command' tagged services
     *
     * @param string $className
     * @param array $tag
     *
     * @throws \Dimfr\Bundle\ChainCommandBundle\Exception\ChainCommandTagException
     */
    public function addChainMember(string $className, array $tag): void
    {
        $member = ChainMember::fromClassNameAndTag($className, $tag);
        $this->chains[$member->getMaster()][] = $member;
    }

    /**
     * Get next commands in chain
     *
     * @param Command $command
     * @return Command[]|[]
     */
    public function getChainCommands(Command $command): array
    {
        $chain = [];

        if ($members = $this->chains[$command->getName()] ?? []) {

            usort($members, fn (ChainMember $a, ChainMember $b) => $a->getPriority() <=> $b->getPriority());

            $applicationCommands = $this->getApplicationCommands($command);

            /** @var ChainMember $member */
            foreach ($members as $member) {
                if ($memberCommand = $applicationCommands[$member->getClassName()] ?? null) {
                    $chain[] = $memberCommand;
                }
            }

        }

        return $chain;
    }

    /**
     * Get next commands aliases in chain
     *
     * @param Command $command
     * @return array
     */
    public function getChainCommandsAliases(Command $command): array
    {
        return array_map(fn (Command $cmd) => $cmd->getName(), $this->getChainCommands($command));
    }
    
    /**
     * @param Command $command
     * @return Command[]
     */
    protected function getApplicationCommands(Command $command): array
    {
        $commands = [];

        foreach ($command->getApplication()->all() as $appCommand) {
            $commands[$appCommand::class] = $appCommand;
        }

        return $commands;
    }

    /**
     * Is command member of a chain
     *
     * @param Command $command
     * @return bool
     */
    public function isChainMember(Command $command): bool
    {
        return null !== $this->getChainMember($command);
    }

    /**
     * Is command master of a chain
     *
     * @param Command $command
     * @return bool
     */
    public function isChainMaster(Command $command): bool
    {
        return array_key_exists($command->getName(), $this->chains);
    }

    /**
     * Get master command alias of chain member
     *
     * @param Command $command
     * @return string|null
     */
    public function getMasterAlias(Command $command): ?string
    {
        if ($member = $this->getChainMember($command)) {
            $masterCommand = $command->getApplication()->all()[$member->getMaster()] ?? null;

            return $masterCommand?->getName();
        }

        return null;
    }

    /**
     * Get chain member of console command
     *
     * @param Command $command
     * @return ChainMember|null
     */
    protected function getChainMember(Command $command): ?ChainMember
    {
        if ($this->chains) {

            foreach ($this->chains as $chain) {
                /** @var ChainMember $member */
                foreach ($chain as $member) {
                    if ($member->getClassName() === $command::class) {
                        return $member;
                    }
                }
            }
        }

        return null;
    }
}