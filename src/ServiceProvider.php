<?php
declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions;

use DKulyk\Eloquent\Extensions\Factories\TypesFactory;

/**
 * Class ServiceProvider
 *
 * @package B2B\Eloquent\Extensions
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('eloquent.types', function ($app) {
            return new TypesFactory($app);
        });
    }
}
