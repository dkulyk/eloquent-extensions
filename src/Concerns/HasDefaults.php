<?php

declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait HasDefaults
 *
 * @property bool $default
 */
trait HasDefaults
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootHasDefaults(): void
    {
        static::saved(function (Model $model) {
            /* @var static $model */
            if ($model->getAttribute($model->getDefaultsColumn())) {
                //unset other default
                $model->unsetDefaultsQuery()
                    ->where($model->getQualifiedDefaultsColumn(), true)
                    ->whereKeyNot($model->getKey())
                    ->update([$model->getDefaultsColumn() => false]);
            }
        });
    }

    protected function initializeHasDefaults(): void
    {
        $this->fillable[] = $field = $this->getDefaultsColumn();
        $this->casts[$field] = 'bool';
    }

    /**
     * Get the name of the "deleted at" column.
     *
     * @return string
     */
    public function getDefaultsColumn(): string
    {
        return 'default';
    }

    /**
     * Get the fully qualified "deleted at" column.
     *
     * @return string
     */
    public function getQualifiedDefaultsColumn(): string
    {
        return "{$this->getTable()}.{$this->getDefaultsColumn()}";
    }


    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return (bool) $this->getAttribute($this->getDefaultAttribute());
    }

    /**
     * @return Builder
     */
    protected function unsetDefaultsQuery(): Builder
    {
        return $this->newQueryWithoutScopes();
    }
}
