<?php

declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Trait HasDynamicRelations
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasDynamicRelations
{
    protected static $dynamicRelations = [];

    public static function relatedTo(string $relation, \Closure $closure)
    {
        static::$dynamicRelations[$relation] = $closure;
    }

    /**
     * Get a relationship.
     *
     * @param  string  $key
     * @return mixed
     */
    public function getRelationValue($key)
    {
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (array_key_exists($key, static::$dynamicRelations) || method_exists($this, $key)) {
            return $this->getRelationshipFromMethod($key);
        }
    }

    public function __call($name, $arguments)
    {
        if (array_key_exists($name, static::$dynamicRelations)) {
            return call_user_func(static::$dynamicRelations[$name], $this);
        }

        return parent::__call($name, $arguments);
    }
}
