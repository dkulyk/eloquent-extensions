<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Factories;

use Psr\Container\ContainerInterface;
use Closure;

/**
 * Class TypesFactory
 */
final class TypesFactory
{
    /**
     * @var ContainerInterface
     */
    protected $app;

    /**
     * @var array
     */
    protected $types = [];

    /**
     * TypesFactory constructor.
     *
     * @param ContainerInterface $app
     */
    public function __construct(ContainerInterface $app)
    {
        $this->app = $app;
    }

    /**
     * @param string       $type
     * @param Closure      $toAttribute
     * @param Closure|null $fromAttribute
     *
     * @return TypesFactory
     */
    public function extend(string $type, Closure $toAttribute, ?Closure $fromAttribute = null): self
    {
        $this->types[$type] = [
            $toAttribute,
            $fromAttribute ?? function ($value) {
                return $value;
            },
        ];

        return $this;
    }

    /**
     * @param mixed  $value
     * @param string $type
     * @param array  ...$options
     *
     * @return mixed
     */
    public function cast($value, string $type, ...$options)
    {
        return \array_key_exists($type, $this->types)
            ? \call_user_func($this->types[$type][0], $value, ...$options)
            : $value;
    }

    /**
     * @param mixed  $value
     * @param string $type
     * @param array  ...$options
     *
     * @return mixed
     */
    public function serialize($value, string $type, ...$options)
    {
        return \array_key_exists($type, $this->types)
            ? \call_user_func($this->types[$type][1], $value, ...$options)
            : $value;
    }
}
