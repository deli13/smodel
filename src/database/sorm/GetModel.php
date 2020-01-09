<?php


namespace deli13\smodel\database\sorm;


trait GetModel
{
    /**
     * Получение записи по ID
     * @param int $id
     * @return static
     */
    public static function getById(int $id): self
    {
        $model = new static();
        $get_by_id = $model->connection->row("select * from " . static::TABLE_NAME . " where " . static::ID_FIELD . "=?", $id);
        $model->loadData($get_by_id);
        return $model;
    }

    /**
     * Поиск одной записи
     * @param array $where
     * @return static|null
     */
    public static function findOne(array $where): ?self
    {
        list($query, $where_row) = QueryBuilder::createWhere($where);
        $model = new static();
        $find_one = $model->connection->row(QueryBuilder::createSelect(static::TABLE_NAME, $query), ...$where_row);
        if (!is_null($find_one)) {
            $model->loadData($find_one);
        } else {
            return null;
        }
        return $model;
    }

    /**
     * Поиск всех записей
     * @param array|null $where
     * @return self[]
     */
    public static function findAll(?array $where = []): array
    {
        $model = new static();
        if (is_array($where) && count($where)>0) {
            list($query, $where_row) = QueryBuilder::createWhere($where);
            $find = $model->connection->run(QueryBuilder::createSelect(static::TABLE_NAME, $query), ...$where_row);
        } else {
            $find = $model->connection->run(QueryBuilder::createSelect(static::TABLE_NAME));
        }
        $model_array = [];
        foreach ($find as $value) {
            $item = new static();
            $item->loadData($value);
            $model_array[] = $item;
        }
        return $model_array;
    }

}