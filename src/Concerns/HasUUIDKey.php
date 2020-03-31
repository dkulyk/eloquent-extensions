<?php

declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Trait HasUUIDKey
 *
 * @property-read string $uuid
 */
trait HasUUIDKey
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootHasUUIDKey(): void
    {
        static::creating(function (Model $model) {
            /* @var self $model */
            $field = $model->getUUIDKey();
            do {
                $uuid = (string) Uuid::uuid4();
            } while ($model->newQuery()
                ->withoutGlobalScopes()
                ->where([$field => $uuid])
                ->exists()
            );

            $model->setAttribute($field, $uuid);
        });
    }

    /**
     * @return string
     */
    public function getUUIDKey(): string
    {
        return 'uuid';
    }
}
