<?php

namespace Somoza\PSB\Publisher\SNS\Resolver;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
interface ResolvesTopics
{
    /**
     * Determines the Topic ARN that corresponds for the given message
     *
     * @param string $messageName
     * @return string
     */
    public function resolve(string $messageName): string;
}
