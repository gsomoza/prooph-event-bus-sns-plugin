<?php

namespace Somoza\PSB\Publisher\SNS\Translator;

use Prooph\Common\Messaging\DomainEvent;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
interface TranslatesDomainEvents
{
    /**
     * @param DomainEvent $event
     * 
     * @return array Message ready to be published with an SnsClient instance
     */
    public function translate(DomainEvent $event): array;
}
