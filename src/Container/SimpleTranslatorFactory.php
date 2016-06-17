<?php

namespace Somoza\PSB\Publisher\SNS\Container;

use Interop\Container\ContainerInterface;
use Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics;
use Somoza\PSB\Publisher\SNS\Translator\SimpleTranslator;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class SimpleTranslatorFactory
{
    /**
     * @param ContainerInterface $container
     * @return SimpleTranslator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ResolvesTopics $resolver */
        $resolver = $container->get(ResolvesTopics::class);

        return new SimpleTranslator($resolver);
    }
}
