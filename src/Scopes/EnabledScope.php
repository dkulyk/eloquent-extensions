<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Scopes;

use DKulyk\Eloquent\Extensions\Concerns\HasEnabled;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class EnabledScope
 *
 * @package B2B\Eloquent\Extensions\Scopes
 */
class EnabledScope implements Scope
{
    /**
     * @var bool
     */
    protected static $withDisabled = false;

    /**
     * @return bool
     */
    public static function isWithDisabled(): bool
    {
        return self::$withDisabled;
    }

    /**
     * @param bool $withDisabled
     */
    public static function setWithDisabled(bool $withDisabled): void
    {
        self::$withDisabled = $withDisabled;
    }

    /**
     * @param callable $callback
     * @param array    ...$args
     *
     * @return mixed
     */
    public static function withDisabled(callable $callback, ...$args)
    {
        $withDisabled = self::isWithDisabled();

        self::setWithDisabled(true);

        try {
            return $callback(...$args);
        } finally {
            self::setWithDisabled($withDisabled);
        }
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  \Illuminate\Database\Eloquent\Model   $model
     *
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        /* @var HasEnabled $model */
        if (!self::isWithDisabled()) {
            $builder->where($model->getQualifiedEnabledColumn(), '=', true);
        }
    }

    /**
     * Extend the query builder with the needed functions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return void
     */
    public function extend(Builder $builder): void
    {
        $builder->macro('withDisabled', function (Builder $builder) {
            return $builder->withoutGlobalScope($this);
        });

        $builder->macro('withoutDisabled', function (Builder $builder) {
            /* @var HasEnabled $model */
            $model = $builder->getModel();

            return $builder->withoutGlobalScope($this)->where($model->getQualifiedEnabledColumn(), true);
        });

        $builder->macro('onlyDisabled', function (Builder $builder) {
            /* @var HasEnabled $model */
            $model = $builder->getModel();

            return $builder->withoutGlobalScope($this)->where($model->getQualifiedEnabledColumn(), false);
        });
    }
}