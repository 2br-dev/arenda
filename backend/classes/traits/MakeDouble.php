<?php


/**
 * MakeDouble trait
 */
trait MakeDouble
{
    /** Приводит переменную к (double) => 0
     * @param string $value
     * @param double or NULL
    */
    protected function makeDouble($value = null)
    {
        if(is_null($value)) {
            return null;
        } 
        $value = (double) $value;
        if($value < 0) {
            return null;
        }
        //
        return $value;
    }
}
