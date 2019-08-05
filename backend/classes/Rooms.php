<?php
require_once DB;

class Rooms 
{

    private $DB = null;
    // Имя таблицы в БД
    private static $table = 'rooms';

    function __construct() 
    {
        $this->DB = DB::connect();
    }


    public function getRooms()
    {
        $query = 'SELECT * FROM ' . self::$table ;
        $params = [];
        $stmt = $this->DB->prepare($query);
        if(!$stmt->execute($params)) {
            return null;
        }
        $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $response;
    }

}