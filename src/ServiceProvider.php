<?php

declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions;

use Closure;
use DKulyk\Eloquent\Extensions\Concerns\HasEnabled;
use DKulyk\Eloquent\Extensions\Factories\TypesFactory;
use DKulyk\Eloquent\Extensions\Nova\Filters\EnabledFilter;
use DKulyk\Eloquent\Extensions\Support\PendingDispatch;
use DtKt\Nova\Events\ResolveFiltersEvent;
use Illuminate\Database\Eloquent\Builder;
use MyCLabs\Enum\Enum;
use Illuminate\Database\Events\{TransactionBeginning, TransactionCommitted, TransactionRolledBack};
use Illuminate\Events\Dispatcher;

/**
 * Class ServiceProvider
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot(Dispatcher $events)
    {
        Builder::macro(
            'legacyHas',
            function ($relation, $operator = '>=', $count = 1, $boolean = 'and', Closure $callback = null) {
                /** @var Builder $this */
                if (strpos($relation, '.') !== false) {
                    return $this->hasNested($relation, $operator, $count, $boolean, $callback);
                }

                $relation = $this->getRelationWithoutConstraints($relation);

                // If we only need to check for the existence of the relation, then we can optimize
                // the subquery to only run a "where exists" clause instead of this full "count"
                // clause. This will make these queries run much faster compared with a count.
                $method = $this->canUseExistsForExistenceCheck($operator, $count)
                    ? 'getRelationExistenceQuery'
                    : 'getRelationExistenceCountQuery';

                $hasQuery = $relation->{$method}(
                    $relation->getRelated()->newQuery(), $this
                );

                // Next we will call any given callback as an "anonymous" scope so they can get the
                // proper logical grouping of the where clauses if needed by this Eloquent query
                // builder. Then, we will be ready to finalize and return this query instance.
                if ($callback) {
                    $hasQuery->callScope($callback);
                }

                return $this->addHasWhere(
                    $hasQuery, $relation, $operator, $count, $boolean
                );
            });

        $events->listen(ResolveFiltersEvent::class, function (ResolveFiltersEvent $event) {
            if (! in_array(HasEnabled::class, trait_uses_recursive($event->getResource()->resource), true)) {
                return;
            }

            $filters = $event->getFilters()->whereInstanceOf(EnabledFilter::class);
            if (count($filters) === 0) {
                $event->appendFilter(new EnabledFilter);
            }
        });

        $events->listen(TransactionBeginning::class, fn() => PendingDispatch::onTransactionBeginning());
        $events->listen(TransactionRolledBack::class, fn() => PendingDispatch::onTransactionRolledBack());
        $events->listen(TransactionCommitted::class, fn() => PendingDispatch::onTransactionCommitted());
    }
}
