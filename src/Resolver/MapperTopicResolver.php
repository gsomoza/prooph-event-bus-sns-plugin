<?php

namespace Somoza\PSB\Publisher\SNS\Resolver;

/**
 * @author Gabriel Somoza <gabriel@somoza.me>
 */
final class MapperTopicResolver implements ResolvesTopics
{
    /** @var array */
    private $map;

    /** @var string */
    private $default;

    /** @var string */
    private $prefix;

    /**
     * @param string $default
     * @param string $prefix
     * @param array $mapping
     */
    public function __construct(string $default, string $prefix = '', array $mapping = [])
    {
        $this->default = $default;
        $this->map = $mapping;
        $this->prefix = $prefix;
    }

    /**
     * Determines the Topic ARN that corresponds for the given message name
     *
     * @param string $messageName
     * @return string
     *
     */
    public function resolve(string $messageName): string
    {
        // subtract the prefix from the path calculation (useful if all mappings share the same prefix prefix)
        if (!empty($this->prefix) && substr($messageName, 0, strlen($this->prefix)) == $this->prefix) {
            $messageName = ltrim(substr($messageName, strlen($this->prefix)), '\\');
        }

        $parts = explode('\\', $messageName);

        if (!empty($this->map)) {
            while (count($parts) > 0) {
                $key = implode('\\', $parts);
                if (!empty($this->map[$key])) {
                    return $this->map[$key];
                }
                array_pop($parts);
            }
        }

        return $this->default;
    }
}
