<?php

namespace Dimfr\Bundle\ChainCommandBundle\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;

/**
 * Trait MokeFactoryTrait
 * @package Dimfr\Bundle\ChainCommandBundle\Tests\Unit
 */
trait MokeFactoryTrait
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


}