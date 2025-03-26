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
        $field = $this->getDefaultsColumn();
        $this->mergeFillable([$field]);
        $this->mergeCasts([$field => 'bool']);
    }

    public function getDefaultsColumn(): string
    {
        return 'default';
    }

    public function getQualifiedDefaultsColumn(): string
    {
        return "{$this->getTable()}.{$this->getDefaultsColumn()}";
    }


    public function isDefault(): bool
    {
        return (bool) $this->getAttribute($this->getDefaultsColumn());
    }

    protected function unsetDefaultsQuery(): Builder
    {
        return $this->newQueryWithoutScopes();
    }
}
