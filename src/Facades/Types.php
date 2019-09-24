<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Facades;

use DKulyk\Eloquent\TypeCasting\Factory;
use Closure;
use Illuminate\Support\Facades\Facade;

/**
 * Class Types
 *
 * @method static Factory extend(string $type, Closure $toAttribute, ?Closure $fromAttribute = null)
 * @method static mixed cast($value, string $type, mixed ...$options)
 * @method static mixed serialize($value, string $type, mixed ...$options)
 * @method static string[] all()
 */
class Types extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'eloquent.types';
    }
}
