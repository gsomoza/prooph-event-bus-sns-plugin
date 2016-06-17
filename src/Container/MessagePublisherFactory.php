<?php

namespace Somoza\PSB\Publisher\SNS\Container;

use Aws\Sns\SnsClient;
use Interop\Container\ContainerInterface;
use Somoza\PSB\Publisher\SNS\MessagePublisher;
use Somoza\PSB\Publisher\SNS\Translator\TranslatesDomainEvents;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class MessagePublisherFactory
{
    /**
     * @param ContainerInterface $container
     * @return MessagePublisher
     */
    public function __invoke(ContainerInterface $container)
    {
        if ($container->has(TranslatesDomainEvents::class)) {
            /** @var TranslatesDomainEvents $translator */
            $translator = $container->get(TranslatesDomainEvents::class);
        } else {
            // build using simple translator factory
            $factory = new SimpleTranslatorFactory();
            $translator = $factory($container);
        }

        /** @var SnsClient $client */
        $client = $container->get(SnsClient::class);

        return new MessagePublisher($client, $translator);
    }
}
