<?php

namespace Dimfr\Bundle\ChainCommandBundle\Chain;

use Dimfr\Bundle\ChainCommandBundle\Exception\ChainCommandTagException;

/**
 * Class ChainMember
 * @package Dimfr\Bundle\ChainCommandBundle\Chain
 */
class ChainMember
{
    /**
     * Class name of the chain member
     *
     * @var string
     */
    protected string $className;
    /**
     * Master command name
     *
     * @var string
     */
    protected string $master;
    /**
     * Priority of the chain member
     *
     * @var int
     */
    protected int $priority = 1;

    /**
     * Create chain member from tag and class name
     *
     * @param string $className
     * @param array{master: string, priority: int} $tag
     * @return static
     * @throws ChainCommandTagException
     */
    public static function fromClassNameAndTag(string $className, array $tag): self
    {
        if (empty($tag['master'])) {
            throw new ChainCommandTagException('Empty master attribute');
        }

        return (new static($className, $tag['master']))->setPriority($tag['priority'] ?? 1);
    }

    public function __construct(string $className, string $master)
    {
        $this->className = $className;
        $this->master = $master;
    }

    /**
     * Get class name attribute
     *
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Set class name attribute
     *
     * @param string $className
     * @return self
     */
    public function setClassName(string $className): self
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get master attribute
     *
     * @return string
     * @return self
     */
    public function getMaster(): string
    {
        return $this->master;
    }

    /**
     * Set master attribute
     *
     * @param string $master
     * @return self
     */
    public function setMaster(string $master): self
    {
        $this->master = $master;

        return $this;
    }

    /**
     * Get priority attribute
     *
     * @return int
     * @return self
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * Set priority attribute
     *
     * @param int $priority
     * @return self
     */
    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
    }


}