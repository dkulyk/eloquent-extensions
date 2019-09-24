<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Trait CanUUID
 *
 * @property-read string $uuid
 */
trait CanUUID
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootCanUUID(): void
    {
        static::creating(function (Model $model) {
            /* @var CanUUID|Model $model */
            do {
                $uuid = Uuid::uuid4();
            } while ($model->newQuery()->where([$model->getUUIDName() => $uuid])->count());
            $model->setAttribute($model->getUUIDName(), (string) $uuid);
        });
    }

    /**
     * @return string
     */
    public function getUUIDName(): string
    {
        return 'uuid';
    }
}
