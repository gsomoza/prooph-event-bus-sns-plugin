<?php

namespace SomozaTest\PSB\Publisher\SNS\Container;

use Aws\Sns\SnsClient;
use Interop\Container\ContainerInterface;

use Somoza\PSB\Publisher\SNS\Container\MessagePublisherFactory;
use Somoza\PSB\Publisher\SNS\MessagePublisher;
use Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics;
use Somoza\PSB\Publisher\SNS\Translator\TranslatesDomainEvents;
use SomozaTest\PSB\Publisher\SNS\TestCase;
use Mockery as m;

/**
 * @author Gabriel Somoza <gabriel.somoza@cu.be>
 */
final class MessagePublisherFactoryTest extends TestCase
{
    /**
     * @return void
     */
    public function testInvokeWithTranslator()
    {
        /** @var TranslatesDomainEvents|m\Mock $translator */
        $translator = m::mock(TranslatesDomainEvents::class);

        /** @var ContainerInterface|m\Mock $container */
        $container = m::mock(ContainerInterface::class);
        $container->shouldReceive([
            'get' => $translator,
            'has' => true
        ])->once()->with(TranslatesDomainEvents::class);
        $container->shouldReceive([
            'get' => m::mock(SnsClient::class),
        ])->once()->with(SnsClient::class);

        $factory = new MessagePublisherFactory();

        $result = $factory($container);

        $this->assertInstanceOf(MessagePublisher::class, $result);
    }

    /**
     * @return void
     */
    public function testInvokeWithoutTranslator()
    {
        /** @var ResolvesTopics|m\Mock $resolver */
        $resolver = m::mock(ResolvesTopics::class);

        /** @var ContainerInterface|m\Mock $container */
        $container = m::mock(ContainerInterface::class);
        $container->shouldReceive([
            'has' => false
        ])->once()->with(TranslatesDomainEvents::class);

        $container->shouldReceive([
            'get' => $resolver,
        ])->once()->with(ResolvesTopics::class);

        $container->shouldReceive([
            'get' => m::mock(SnsClient::class),
        ])->once()->with(SnsClient::class);

        $factory = new MessagePublisherFactory();

        $result = $factory($container);

        $this->assertInstanceOf(MessagePublisher::class, $result);
    }
}
