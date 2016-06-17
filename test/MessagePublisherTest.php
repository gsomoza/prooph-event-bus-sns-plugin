<?php

namespace SomozaTest\PSB\Publisher\SNS;

use Assert\InvalidArgumentException;
use Aws\Sns\SnsClient;
use Mockery as m;
use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventStore\EventStore;
use Rhumsaa\Uuid\Uuid;
use Somoza\PSB\Publisher\SNS\MessagePublisher;
use Somoza\PSB\Publisher\SNS\Translator\TranslatesDomainEvents;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class MessagePublisherTest extends TestCase
{
    /**
     * @return void
     */
    public function testSetUp()
    {
        $publisher = $this->getPublisherInstance();

        /** @var \Mockery\Mock|EventStore $eventStore */
        $eventStore = m::mock(EventStore::class);
        $eventStore->shouldReceive('getActionEventEmitter->attachListener')->once()->with(m::type('string'), m::type('array'));
        $publisher->setUp($eventStore);
    }

    /**
     * @return void
     */
    public function testOnCommitPostChecksTypesOfRecordedEvents()
    {
        $publisher = $this->getPublisherInstance();


        /** @var ActionEvent|m\Mock $event */
        $event = m::mock(ActionEvent::class);
        $event->shouldReceive('getParam')
            ->with('recordedEvents', m::type('array'))
            ->once()
            ->andReturn([m::mock(DomainEvent::class), 'invalid type']);

        $this->expectException(InvalidArgumentException::class);

        $publisher->onCommitPost($event);
    }

    /**
     * @param array $recordedEvents
     * @dataProvider recordedEventsProvider
     * @return void
     */
    public function testOnCommit(array $recordedEvents = [])
    {
        $recordedEvents = array_map(function($data) {
            return TestDomainEvent::fromArray($data);
        }, $recordedEvents);

        /** @var SnsClient|m\Mock $client */
        $client = m::mock(SnsClient::class);
        $client->shouldReceive('publish')
            ->with(m::on(function ($value) {
                return is_array($value) && isset($value['Subject'], $value['Message'], $value['TopicArn']);
            }))
            ->times(count($recordedEvents));

        /** @var TranslatesDomainEvents|m\Mock $translator */
        $translator = m::mock(TranslatesDomainEvents::class);
        $translator->shouldReceive('translate')
            ->times(count($recordedEvents))
            ->andReturn(['Subject' => 1, 'Message' => 1, 'TopicArn' => 1]);

        $publisher = new MessagePublisher($client, $translator);

        /** @var ActionEvent|m\Mock $event */
        $event = m::mock(ActionEvent::class);
        $event->shouldReceive('getParam')
            ->with('recordedEvents', m::type('array'))
            ->once()
            ->andReturn($recordedEvents);

        $publisher->onCommitPost($event);
    }

    /**
     * @return array
     */
    public function recordedEventsProvider()
    {
        return [
            [ // empty
                []
            ],
            [ // simple
                [
                    [
                        'uuid' => Uuid::uuid4(),
                        'message_name' => 'test1',
                        'version' => '1',
                        'metadata' => [],
                        'created_at' => new \DateTime(),
                        'payload' => [],
                    ],
                    [
                        'uuid' => Uuid::uuid4(),
                        'message_name' => 'test2',
                        'version' => '1',
                        'metadata' => [],
                        'created_at' => new \DateTime(),
                        'payload' => ['foo' => 'bar'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param SnsClient $client
     * @return MessagePublisher
     */
    private function getPublisherInstance(SnsClient $client = null)
    {
        if (null === $client) {
            /** @var SnsClient $client */
            $client = m::mock(SnsClient::class);
        }

        /** @var \Somoza\PSB\Publisher\SNS\Translator\TranslatesDomainEvents|m\Mock $translator */
        $translator = m::mock(TranslatesDomainEvents::class);
        $publisher = new MessagePublisher($client, $translator);

        return $publisher;
    }
}
