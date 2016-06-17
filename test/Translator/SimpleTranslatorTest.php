<?php

namespace SomozaTest\PSB\Publisher\SNS\Translator;

use Mockery as m;
use Prooph\Common\Messaging\DomainEvent;
use Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics;
use Somoza\PSB\Publisher\SNS\Translator\SimpleTranslator;
use SomozaTest\PSB\Publisher\SNS\TestCase;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class SimpleTranslatorTest extends TestCase
{
    /**
     * @return void
     */
    public function testTranslate()
    {
        /** @var DomainEvent|m\Mock $event */
        $event = m::mock(DomainEvent::class);
        $event->shouldReceive([
            'messageName' => 'testMessage',
            'toArray' => ['foo' => 'bar'],
        ])->atLeast(1);

        /** @var \Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics|m\Mock $resolver */
        $resolver = m::mock(\Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics::class);
        $resolver->shouldReceive([
            'resolve' => 'testTopic'
        ])->once();

        $translator = new SimpleTranslator($resolver);
        $translator->translate($event);
    }
}
