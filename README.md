# Prooph EventBus SNS Plugin

A plugin that publishes events that go through the PEB (Prooph Event Bus) to AWS SNS topics.

## Installation

```
composer install gsomoza/psb-sns-plugin
```

## Usage

You can use the provided container-interop factory 
`MessagePublisherFactory` to easily create an instance of the plugin 
using a container. If you do so, you must register at least one Topic 
Resolver under the key `Somoza\PSB\Publisher\SNS\Resolver\ResolvesTopics` 
in your container - the object must implement the interface referenced by 
that key.

If you also want to customize how `DomainEvents` are translated into
SNS messages you can also register a Domain Event Translator under 
key `Somoza\PSB\Publisher\SNS\Translator\TranslatesDomainEvents` - like
above, the object must implement the interface indicated by that key.

If you're using containers, once you have a MessagePublisher registered 
in your container, you can add it as a plugin for the Event Bus. 
Typically this would be be a config under 
`prooph/service_bus/event_bus/plugins`.

