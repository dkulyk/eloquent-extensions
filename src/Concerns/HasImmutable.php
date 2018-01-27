<?php
declare(strict_types=1);

namespace B2B\Eloquent\Extensions\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Trait HasImmutable
 *
 * @package B2B\TCA\Core\Entities\Concerns
 */
trait HasImmutable
{
    use SoftDeletes;
    private static $immutableForEntities = [];

    /**
     * @param string $class
     *
     * @return void
     */
    public static function immutableFor(string $class)
    {
        $morphMap = Relation::morphMap();

        if (!empty($morphMap) && \in_array($class, $morphMap, true)) {
            /** @noinspection CallableParameterUseCaseInTypeContextInspection */
            $class = \array_search($class, $morphMap, true);
        }
        self::$immutableForEntities[] = $class;
    }

    /**
     * @param Builder $query
     *
     * @return bool
     */
    protected function performUpdate(Builder $query): bool
    {
        if (\in_array($this->entity_type, self::$immutableForEntities, true)) {
            $this->delete();
            $this->{$this->getKeyName()} = null;
            $this->{$this->getDeletedAtColumn()} = null;
            return $this->performInsert($query);
        }

        return parent::performUpdate($query);
    }
}
