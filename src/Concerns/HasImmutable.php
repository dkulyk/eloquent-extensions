<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Trait HasImmutable
 *
 * @package B2B\TCA\Core\Entities\Concerns
 */
trait HasImmutable
{
    use SoftDeletes;

    /**
     * Get immutable fields.
     *
     * @return array|null
     */
    public function getImmutable(): ?array
    {
        return \property_exists($this, 'immutable') ? (array)$this->immutable : null;
    }

    /**
     * Set immutable fields.
     *
     * @param array $immutable
     *
     * @return static
     */
    public function setImmutable(array $immutable): self
    {
        if (!\property_exists($this, 'immutable')) {
            throw new \RuntimeException('Immutable not supported.');
        }
        $this->immutable = $immutable;

        return $this;
    }

    public static function bootHasImmutable(): void
    {
        static::saving(function (Model $model) {
            if (!$model->exists) {
                return;
            }
            /* @var Model|HasImmutable $model */
            $immutable = $model->getImmutable();
            if ($immutable === null) {
                return;
            }

            if ($model->isDirty($immutable)) {
                $model->newQueryWithoutScopes()
                    ->whereKey($model->getKey())
                    ->delete();
                $model->setAttribute($model->getKeyName(), null);
                $model->exists = false;
            }
        });
    }
}
