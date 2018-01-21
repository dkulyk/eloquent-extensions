<?php
declare(strict_types=1);

namespace B2B\Eloquent\Extensions\Support;

use Illuminate\Support\Collection as BaseCollection;

/**
 * Class Collection
 *
 * @package B2B\Eloquent\Extensions\Support
 */
class Collection extends BaseCollection
{
    /**
     * @var callable[]
     */
    private $listeners = [];

    /**
     * Add change listener.
     *
     * @param callable $listener
     *
     * @return static
     */
    public function onChange(callable $listener): self
    {
        $this->listeners[] = $listener;

        return $this;
    }

    /**
     * Cleanup collection.
     *
     * @return Collection
     */
    public function clean(): self
    {
        $this->items = [];

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($key, $value)
    {
        parent::offsetSet($key, $value);
        foreach ($this->listeners as $listener) {
            $listener($this, $key, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($key)
    {
        parent::offsetUnset($key);
        foreach ($this->listeners as $listener) {
            $listener($this, $key);
        }
    }
}