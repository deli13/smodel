<?php


namespace deli13\smodel\database;


use ParagonIE\EasyDB\EasyDB;

class Database
{
    protected static $_instance;
    private $database;

    /**
     * Получение инстанса БД
     * @return Database
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Установка соединения
     * @param EasyDB $easyDB
     */
    public function setConnection(EasyDB $easyDB)
    {
        $this->database = $easyDB;
    }

    /**
     * Получение соединения
     * @return EasyDB
     */
    public function getConnection(): EasyDB
    {
        return $this->database;
    }
}