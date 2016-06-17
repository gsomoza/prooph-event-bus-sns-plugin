<?php

namespace SomozaTest\PSB\Publisher\SNS;
use Prooph\Common\Messaging\DomainEvent;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
class TestDomainEvent extends DomainEvent
{

    /**
     * Return message payload as array
     *
     * The payload should only contain scalar types and sub arrays.
     * The payload is normally passed to json_encode to persist the message or
     * push it into a message queue.
     *
     * @return array
     */
    public function payload()
    {
        return [];
    }

    /**
     * This method is called when message is instantiated named constructor fromArray
     *
     * @param array $payload
     * @return void
     */
    protected function setPayload(array $payload)
    {
        // do nothing
    }
}
