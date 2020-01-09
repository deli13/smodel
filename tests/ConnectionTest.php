<?php
use \deli13\smodel\database\Database;
require_once dirname(__FILE__)."/data/TableData.php";
class ConnectionTest extends \PHPUnit\Framework\TestCase
{
    protected $dbname="test";

    public function testDB(){
        $factory=\ParagonIE\EasyDB\Factory::create('mysql:host=127.0.0.1;dbname=test', "root", "1234");
        Database::getInstance()->setConnection($factory);
        $this->assertEquals($factory,Database::getInstance()->getConnection());
    }

    /**
     * @after
     */
    public function testCreate(){
        $connect=Database::getInstance()->getConnection();
        $connect->q("create table if not exists test_table (ID int auto_increment primary key , name varchar(255)) default charset utf8");
        $data=TableData::getById(1);
        $this->assertNull($data);
    }

}