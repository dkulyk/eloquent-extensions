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
            if ($model->default) {
                //unset other default
                $model->unsetDefaultsQuery()
                    ->where($model->getQualifiedDefaultsColumn(), true)
                    ->whereKeyNot($model->getKey())
                    ->update([$model->getDefaultsColumn() => false]);
            }
        });
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
    protected function getDefaultAttribute(): bool
    {
        return (bool) ($this->attributes[$this->getDefaultsColumn()] ?? false);
    }

    /**
     * @param bool $value
     *
     * @return static
     */
    protected function setDefaultAttribute($value): self
    {
        $this->attributes[$this->getDefaultsColumn()] = (bool) $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->getDefaultAttribute();
    }

    /**
     * @return Builder
     */
    protected function unsetDefaultsQuery(): Builder
    {
        return $this->newQueryWithoutScopes();
    }
}