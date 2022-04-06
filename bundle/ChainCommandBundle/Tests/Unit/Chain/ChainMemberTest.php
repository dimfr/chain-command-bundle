<?php

namespace Dimfr\Bundle\ChainCommandBundle\Tests\Unit\Chain;

use Dimfr\Bundle\ChainCommandBundle\Chain\ChainMember;
use Dimfr\Bundle\ChainCommandBundle\Exception\ChainCommandTagException;
use PHPUnit\Framework\TestCase;

/**
 * Class ChainMemberTest
 * @package Dimfr\Bundle\ChainCommandBundle\Tests\Unit\Chain
 */
class ChainMemberTest extends TestCase
{
    /**
     * Test chain member creation via fromClassNameAndTag method
     */
    public function testFromClassNameAndTag()
    {
        [$className, $tag] = $this->getTestData();

        $member = ChainMember::fromClassNameAndTag($className, $tag);

        $this->assertInstanceOf(ChainMember::class, $member);

        $this->assertEquals($className, $member->getClassName());
        $this->assertEquals($tag['master'], $member->getMaster());
        $this->assertEquals($tag['priority'], $member->getPriority());
    }

    /**
     * Test chain member creation via fromClassNameAndTag method with invalid tag data
     *
     * @throws ChainCommandTagException
     */
    public function testFromClassNameAndTagEmptyMaster()
    {
        $this->expectException(ChainCommandTagException::class);

        ChainMember::fromClassNameAndTag(static::class, []);
    }

    protected function getTestData(): array
    {
        return [static::class, ['master' => 'foo:bar', 'priority' => true]];
    }
}