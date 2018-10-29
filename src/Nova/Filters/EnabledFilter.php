<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

/**
 * Class EnabledFilter
 * @package DtKt\Nova\Filters
 */
class EnabledFilter extends Filter
{
    public function id()
    {
        return 'enabled';
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request              $request
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed                                 $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        switch ($value) {
            case 'all':
                return $query->withDisabled();
            case'disabled';
                return $query->withDisabled()->where('enabled', 0);
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function options(Request $request)
    {
        return [
            __('With disabled') => 'all',
            __('Only disabled') => 'disabled'
        ];
    }

    public function name()
    {
        return __('Enabled') . ':';
    }
}
