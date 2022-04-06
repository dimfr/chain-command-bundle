<?php

namespace Dimfr\Bundle\ChainCommandBundle\Tests\Unit\Chain;

use App\Kernel;
use Dimfr\Bundle\ChainCommandBundle\Tests\Unit\MokeFactoryTrait;
use Monolog\Test\TestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;

/**
 * Class ChainProviderTest
 * @package Dimfr\Bundle\ChainCommandBundle\Tests\Unit\Chain
 */
class ChainProviderTest extends TestCase
{
    use MokeFactoryTrait;

    protected function getApplication(): Application
    {
        return new Application(new Kernel('dev', true));
    }
}