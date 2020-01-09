<?php


namespace deli13\smodel\database\sorm;


use deli13\smodel\database\Database;
use deli13\smodel\database\errors\FieldException;
use deli13\smodel\database\errors\ModelLoadException;
use deli13\smodel\database\errors\ValidationException;

/**
 * Абстрактный класс для ORM
 * Class Model
 * @package App\model\Base
 * @property string ID_FIELD
 * @property string TABLE_NAME
 */
abstract class Model
{
    use GetModel, SetModel;

    protected $field = [];
    protected $change_field = []; //запись старых значений
    protected $connection;
    protected $load = false;

    /**
     * Model constructor.
     * @throws ModelLoadException
     */
    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
        if(empty(static::TABLE_NAME) || empty(static::ID_FIELD)){
            throw new ModelLoadException("Не указаны название таблицы или поле ID");
        }
    }

    /**
     * Геттер полей таблицы
     * @param $name
     * @return mixed
     * @throws \ErrorException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->field)) {
            return $this->field[$name];
        } else {
            throw new \ErrorException("Поле " . $name . " не найдено");
        }
    }

    /**
     * Сеттер полей таблицы
     * @param $name
     * @param $value
     * @throws \ErrorException
     */
    public function __set($name, $value)
    {
        if (count($this->field) > 0 && array_key_exists($name, $this->field)) {
            $this->change_field[$name] = $this->field[$name]; //Записываем старое значение
            $this->field[$name] = $value;
        } else if (count($this->field) > 0 && !array_key_exists($name, $this->field) && $this->load) {
            throw new FieldException("Поле $name не найдено");
        } else {
            $this->field[$name] = $value;
        }
    }

    /**
     * Проверка того что поле существует
     * @param $name
     * @return bool
     */
    public function hasField($name)
    {
        return array_key_exists($name, $this->field);
    }

    /**
     * Загрузка данных в класс из БД
     * @param array $values
     */
    protected function loadData(array $values)
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
        $this->load = true;
    }


    /**
     * Валидация того что запись загружена
     * @return bool
     * @throws \ErrorException
     */
    private function valid()
    {
        if (count($this->field) == 0) {
            throw new ValidationException("Ошибка записи");
        }
        return true;
    }

    /**
     * Получение списка изменённых значений
     * @return array
     */
    public function getDirtyFieldValue(): array
    {
        $dirty = [];
        foreach ($this->change_field as $key => $value) {
            if ($this->field[$key] != $this->change_field[$key]) {
                $dirty[$key] = $this->field[$key];
            }
        }
        return $dirty;
    }

    /**
     * Сохраненеие записей
     */
    public function save()
    {
        if ($this->load) {
            $this->update();
        } else {
            $this->insert();
        }
    }

    public function delete()
    {
        $this->connection->delete(static::TABLE_NAME, [
            static::ID_FIELD => $this->field[static::ID_FIELD]
        ]);
        $this->field = null;
        $this->connection = null;
    }
}