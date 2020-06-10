<?php

declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasDynamicRelations
 * @mixin Model
 * @deprecated Use Model::resolveRelationUsing()
 */
trait HasDynamicRelations
{
    /**
     * @param  string  $relation
     * @param  \Closure  $closure
     * @deprecated Use Model::resolveRelationUsing()
     */
    public static function relatedTo(string $relation, \Closure $closure)
    {
        static::resolveRelationUsing($relation, $closure);
    }
}
