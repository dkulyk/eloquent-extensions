<?php

declare(strict_types=1);

namespace DKulyk\Eloquent\Extensions\Support;

class PendingDispatch extends \Illuminate\Foundation\Bus\PendingDispatch
{
    static array $jobs = [];

    static function onTransactionBeginning()
    {
        //add new scope to stack
        self::$jobs[] = [];
    }

    static function onTransactionRolledBack()
    {
        if (count(self::$jobs)) {
            //remove last scope from stack
            array_pop(self::$jobs);
        }
    }

    static function onTransactionCommitted()
    {
        switch (count(self::$jobs)) {
            case 0:
                break;
            case 1:
                //dispatch last scope and remove it
                array_map('dispatch', array_pop(self::$jobs));
                break;
            default:
                // merge two last scopes(main transaction is not ended yet)
                array_push(self::$jobs, array_merge(array_pop(self::$jobs), array_pop(self::$jobs)));
                break;
        }
    }

    public function __destruct()
    {
        if (empty(self::$jobs)) {
            parent::__destruct();
        } else {
            //adds the job to las scope
            self::$jobs[count(self::$jobs) - 1][] = $this->job;
        }
    }
}
