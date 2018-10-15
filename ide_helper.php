<?php
declare(strict_types=1);

namespace Illuminate\Database\Eloquent {

    /**
     * Class Builder
     *
     * @package Illuminate\Database\Eloquent
     * @method $this withDisabled()
     * @method $this withoutDisabled()
     * @method $this onlyDisabled()
     * @method $this withTrashed()
     * @method $this withoutTrashed()
     * @method $this onlyTrashed()
     * @method static restore()
     * @method $this legacyHas($relation, $operator = '>=', $count = 1, $boolean = 'and', \Closure $callback = null)
     */
    class Builder
    {
    }
}

namespace Illuminate\Support {

    /**
     * @method $this nullable()
     * @method $this unique()
     * @method $this index()
     * @method $this default($value)
     * @method $this references(string $field)
     * @method $this on(string $table)
     * @method $this onUpdate(string $action)
     * @method $this onDelete(string $action)
     * @method $this after(string $field)
     */
    class Fluent
    {
    }
}