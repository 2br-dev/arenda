<?php


/**
 * TraitDate trait
 */
trait MakeDate
{
    /** Валидирует дату Unix в YYYY-MM-DD
     * @param string $value
     * @param date or NULL
    */
    protected function makeDate($value = null)
    {
        if(is_null($value)) {
            return null;
        }
        //
        if(strripos($value, "-") !== false || strripos($value, ".") !== false || strripos($value, " ") !== false) {
            return null;
        }
        //
        $value = (int) $value;
        //
        if($value <= 0) {
            return null;
        } 
        //
        return date('Y-m-d', $value);
    }
}
