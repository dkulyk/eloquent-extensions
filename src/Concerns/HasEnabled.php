<?php

declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Concerns;

use DKulyk\Eloquent\Extensions\Scopes\EnabledScope;

/**
 * Trait HasEnabled
 *
 * @property bool $enabled
 */
trait HasEnabled
{
    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootHasEnabled(): void
    {
        static::addGlobalScope(new EnabledScope());
    }

    protected function initializeHasEnabled(): void
    {
        $field = $this->getEnabledColumn();
        $this->mergeFillable([$field]);
        $this->mergeCasts([$field => 'bool']);

    }

    /**
     * Get the name of the "deleted at" column.
     *
     * @return string
     */
    public function getEnabledColumn(): string
    {
        return defined('static::ENABLED') ? static::ENABLED : 'enabled';
    }

    /**
     * Get the fully qualified "deleted at" column.
     *
     * @return string
     */
    public function getQualifiedEnabledColumn(): string
    {
        return $this->qualifyColumn($this->getEnabledColumn());
    }
}
