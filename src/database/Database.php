<?php


namespace deli13\smodel\database;


use ParagonIE\EasyDB\EasyDB;

class Database
{
    protected static $_instance;
    private $database;

    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance=new self();
        }
        return self::$_instance;
    }

    public function setDatabase(EasyDB $easyDB){
        $this->database=$easyDB;
    }

    public function getDatabase():EasyDB
    {
        return $this->database;
    }
}