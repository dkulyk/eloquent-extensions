<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Trait HasMorphEntity
 *
 * @package B2B\TCA\Core\Entities\Concerns
 * @property-read Model  $entity
 * @property-read string $entity_type
 * @property-read int    $entity_id
 */
trait HasMorphEntity
{
    /**
     * @return MorphTo
     */
    public function entity(): MorphTo
    {
        return $this->morphTo('entity');
    }

    /**
     * @param Model        $entity
     * @param Closure|null $callback
     *
     * @return Builder
     */
    public static function entitiesForQuery(Model $entity, Closure $callback = null): Builder
    {
        /* @var Model $model */
        $model = new static();
        /* @var MorphTo $relation */
        $relation = $model->entity();

        return $model->newQuery()
            ->whereIn($relation->getMorphType(), [$entity->getMorphClass(), get_class($entity)])
            ->where($relation->getForeignKeyName(), $entity->getKey())
            ->when($callback, function (Builder $builder, Closure $callback) {
                return $builder->where($callback);
            });
    }

    /**
     * @param Model        $entity
     * @param Closure|null $callback
     * @param array        $data
     *
     * @return Model|static
     */
    public static function entityFor(Model $entity, Closure $callback = null, array $data = []): Model
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        $model = self::entitiesForQuery($entity, $callback)->first();

        if ($model === null) {
            $model = (new static())
                ->entity()
                ->associate($entity)
                ->fill($data);
        }
        return $model;
    }

    /**
     * @param Model        $entity
     * @param Closure|null $callback
     *
     * @return Collection
     */
    public static function entitiesFor(Model $entity, Closure $callback = null): Collection
    {
        return self::entitiesForQuery($entity, $callback)->get();
    }
}
