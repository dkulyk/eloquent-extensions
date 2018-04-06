<?php
declare(strict_types=1);

namespace Illuminate\Database\Eloquent {

    /**
     * Class Builder
     *
     * @package Illuminate\Database\Eloquent
     */
    class Builder
    {
        /**
         * @return Builder
         */
        public function withDisabled(): Builder
        {
            return $this;
        }

        /**
         * @return Builder
         */
        public function withoutDisabled(): Builder
        {
            return $this;
        }
    }
}
