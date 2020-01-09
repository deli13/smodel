<?php


namespace deli13\smodel\database\sorm;


class QueryBuilder
{
    /**
     * Создание условия
     * @param array $where
     * @return string
     */
    public static function createWhere(array $where): array
    {
        $str_where = " WHERE ";
        $arr_where = [];
        $arr_val = [];
        foreach ($where as $key => $value) {
            $arr_where[] = "$key=?";
            $arr_val[] = $value;
        }
        return [$str_where . join(" and ", $arr_where), $arr_val];
    }

    /**
     * Создание селектового запроса
     * @param string $table_name
     * @param string|null $where
     * @return string
     */
    public static function createSelect(string $table_name, ?string $where = null)
    {
        $select = "SELECT * FROM " . $table_name;
        return $where ? $select . " " . $where : $select;
    }
}