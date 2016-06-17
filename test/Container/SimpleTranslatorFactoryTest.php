<?php

namespace SomozaTest\PSB\Publisher\SNS\Container;

use Interop\Container\ContainerInterface;
use Somoza\PSB\Publisher\SNS\Container\SimpleTranslatorFactory;
use Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics;
use Somoza\PSB\Publisher\SNS\Translator\SimpleTranslator;
use SomozaTest\PSB\Publisher\SNS\TestCase;
use Mockery as m;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class SimpleTranslatorFactoryTest extends TestCase
{
    /**
     * testFactory
     * @return void
     */
    public function testFactory()
    {
        /** @var ResolvesTopics|m\Mock $resolver */
        $resolver = m::mock(ResolvesTopics::class);

        /** @var ContainerInterface|m\Mock $container */
        $container = m::mock(ContainerInterface::class);
        $container->shouldReceive(['get' => $resolver])->once()->with(ResolvesTopics::class);

        $factory = new SimpleTranslatorFactory();
        $result = $factory($container);

        $this->assertInstanceOf(SimpleTranslator::class, $result);
    }
}
