<?php


/**
 * MakeString trait
 */
trait MakeString
{
    /** Валидирует строку
     * @param string $value
     * @param string or NULL
    */
    protected function makeString($value = null)
    {
        if(is_null($value)) {
            return null;
        } 
        $value = trim($value);
        if(strlen($value) <= 0) {
            return null;
        }
        //
        return $value;
    }
}
