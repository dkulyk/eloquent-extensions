<?php
declare(strict_types=1);
namespace DKulyk\Eloquent\Extensions\Concerns;

use DKulyk\Eloquent\Extensions\Support\Collection;
use InvalidArgumentException;

/**
 * Trait HasSecuredOptions
 *
 * @property Collection|iterable $options
 */
trait HasSecuredOptions
{
    /**
     * @var Collection
     */
    protected $optionsCollection;

    /**
     * @return Collection
     */
    public function getOptionsAttribute(): Collection
    {
        /** @noinspection PhpUnusedParameterInspection */
        return $this->optionsCollection ?? $this->optionsCollection =
                (new Collection((array)\json_decode($this->attributes['options'] ?? '{}', true)))
                    ->onChange(function (Collection $collection) {
                        $this->attributes['options'] = $this->asJson($collection);
                    });
    }

    /**
     * @param iterable $value
     *
     * @return static
     * @throws InvalidArgumentException
     */
    public function setOptionsAttribute(iterable $value)
    {
        $options = $this->getOptionsAttribute()->clean();
        if (\is_iterable($value)) {
            foreach ($value as $key => $val) {
                $options->put($key, $val);
            }

            return $this;
        }
        throw new InvalidArgumentException('Value must be iterable');
    }
}