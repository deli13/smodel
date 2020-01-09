<?php


namespace deli13\smodel\database\sorm;


trait SetModel
{

    /**
     * Обновление записи
     * @throws \ErrorException
     */
    private function update()
    {
        $this->valid();
        $new_value = $this->getDirtyFieldValue();
        if (count($new_value) > 0) {
            $this->connection->update(
                static::TABLE_NAME,
                $new_value,
                [
                    static::ID_FIELD => $this->field[static::ID_FIELD]
                ]
            );;
        }
    }


    private function insert()
    {
        $this->valid();
        $this->field[static::ID_FIELD] = $this->connection->insert(static::TABLE_NAME, $this->field);
        $this->load = true;
        return true;
    }
}