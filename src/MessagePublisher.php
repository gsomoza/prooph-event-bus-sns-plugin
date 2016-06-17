<?php

namespace Somoza\PSB\Publisher\SNS;

use Assert\Assertion;
use Aws\Sns\SnsClient;
use Prooph\Common\Event\ActionEvent;
use Prooph\Common\Messaging\DomainEvent;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Plugin\Plugin;
use Somoza\PSB\Publisher\SNS\Translator\TranslatesDomainEvents;

/**
 * Uses an already-configured AWS SDK SNS client to send a message to a SNS topic
 *
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class MessagePublisher implements Plugin
{
    /** @var SnsClient */
    private $client;

    /** @var TranslatesDomainEvents */
    private $translator;

    /**
     * @param SnsClient $client
     * @param TranslatesDomainEvents $translator
     */
    public function __construct(SnsClient $client, TranslatesDomainEvents $translator)
    {
        $this->client = $client;
        $this->translator = $translator;
    }

    /**
     * @param EventStore $eventStore
     * @return void
     */
    public function setUp(EventStore $eventStore)
    {
        $eventStore->getActionEventEmitter()->attachListener('commit.post', [$this, 'onCommitPost']);
    }

    /**
     * @param ActionEvent $actionEvent
     *
     * @return void
     */
    public function onCommitPost(ActionEvent $actionEvent)
    {
        /** @var DomainEvent[] $recordedEvents */
        $recordedEvents = $actionEvent->getParam('recordedEvents', []);
        Assertion::allIsInstanceOf($recordedEvents, DomainEvent::class);
        
        foreach ($recordedEvents as $recordedEvent) {
            $message = $this->translator->translate($recordedEvent);
            $this->client->publish($message);
        }
    }

}
