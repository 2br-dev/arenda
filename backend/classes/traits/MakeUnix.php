<?php


/**
 * MakeUnix trait
 */
trait MakeUnix
{
    /** Валидирует дату YYYY-MM-DD в MakeUnix
     * @param string $value
     * @param date or NULL
    */
    protected function makeUnix($value = null)
    {
        if(is_null($value)) {
            return null;
        }
        //
        if(strlen($value) !== 10) {
            return null;
        }
        //
        return (int) date('U', strtotime($value));
    }
}
