<?php

namespace Somoza\PSB\Publisher\SNS\Translator;

use Prooph\Common\Messaging\DomainEvent;
use Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class SimpleTranslator implements TranslatesDomainEvents
{
    /** @var ResolvesTopics */
    private $topicResolver;

    /**
     * @param ResolvesTopics $topicResolver
     */
    public function __construct(ResolvesTopics $topicResolver)
    {
        $this->topicResolver = $topicResolver;
    }

    /**
     * @param DomainEvent $event
     *
     * @return array Message ready to be published with an SnsClient instance
     */
    public function translate(DomainEvent $event): array
    {
        return [
            'Subject' => $event->messageName(),
            'Message' => \GuzzleHttp\json_encode($event->toArray()),
            'TopicArn' => $this->topicResolver->resolve($event->messageName()),
        ];
    }
}
