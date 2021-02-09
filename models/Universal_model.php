<?php

class Universal_model extends Index
{

    function __construct()
    {
        $this->database = $this->getDatabase();
    }

    function select($table, $fields = '*', $where = '', $order = '', $group = '', $limit = '', $keyName = FALSE)
    {
        if (empty($table)) exit;

        if (empty($where)) {
            $where = ' 1 ';
        } else if (is_array($where)) {
            $whereClone = array();

            foreach ($where as $index => $key) {
                if (gettype($key) == 'string') $key = "'$key'";

                $whereClone[] = $index . ' = ' . $key;
            }

            $where = implode(' AND ', $whereClone);
        }
        $where = ' WHERE ' . $where;

        if (!empty($order)) {
            $order = ' ORDER BY ' . $order;
        }

        if (!empty($group)) {
            $group = ' GROUP BY ' . $group;
        }

        if (!empty($limit)) {
            if (is_array($limit)) {
                $limit = implode(',', $limit);
            }
            $limit = ' LIMIT ' . $limit;
        }

        $query = "SELECT $fields FROM " . $table . $where . $group . $order . $limit;
        $result = mysqli_query($this->database, $query);

        if (!empty($result->num_rows)) {
            while ($row = mysqli_fetch_assoc($result)) {
                if (!empty($keyName)) {
                    $data[$row[$keyName]] = $row;
                } else {
                    $data[] = $row;
                }
            }
            return $data;
        }

        return array();
    }

    function insert($table, $insert)
    {
        if (empty($table) || empty($insert)) exit;

        $insertKeys = array();
        $insertValues = array();

        foreach ($insert as $index => $key) {
            if (gettype($key) == 'string') $key = "'$key'";

            $insertKeys[] = "`$index`";
            $insertValues[] = $key;
        }

        $insertKeys = "(" . implode(', ', $insertKeys) . ")";
        $insertValues = "(" . implode(', ', $insertValues) . ")";

        $query = "INSERT INTO `$table` $insertKeys VALUES $insertValues";
        mysqli_query($this->database, $query);
    }

    function update($table, $updatedFileds, $where)
    {
        if (empty($table) || empty($updatedFileds) || empty($where)) exit;

        $update = array();
        foreach ($updatedFileds as $index => $key) {
            if (gettype($key) == 'string') $key = "'$key'";

            $update[] = "`$index`" . '=' . $key;
        }

        $update = implode(', ', $update);

        $query = "UPDATE $table SET $update WHERE $where";
        mysqli_query($this->database, $query);
    }

    function delete_values($table, $where)
    {
        if (empty($table) || empty($where)) exit;

        $query = "DELETE FROM `$table` WHERE $where";
        mysqli_query($this->database, $query);
    }

    function get_query($query, $getArray = TRUE)
    {
        if (empty($query)) exit;

        $result = mysqli_query($this->database, $query);

        if (!empty($result->num_rows) && !empty($getArray)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }

        return array();
    }

    function tableFields($table)
    {
        $fields = $this->get_query("SHOW COLUMNS FROM `$table`");
        $data = [];
        foreach ($fields as $key) {
            $data[] = $key['Field'];
        }
        return $data;
    }

    public function getAllowedFields($array, $table, $xss = FALSE)
    {
        $columns = $this->tableFields($table);
        $data = [];
        foreach ($array as $bin => $value) {
            if (in_array($bin, $columns)) {
                if (!empty($xss) && gettype($value) == 'string') $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
                $data[$bin] = $value;
            }
        }
        return $data;
    }
}
