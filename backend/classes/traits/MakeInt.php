<?php


/**
 * TraitName trait
 */
trait MakeInt
{
    /** Приводит переменную к (int) > 0
     * @param string $value
     * @return int or NULL
    */
    protected function makeInt($value = null)
    {
        if(is_null($value)) {
            return null;
        } 
        $value = (int) $value;
        if($value <= 0) {
            return null;
        }
        //
        return $value;
    }
}
