<?php
    namespace Vendor\Database\Postgressql;
    require_once(__DIR__."/factory.php");
    require_once(__DIR__."/rows.php");
    class SqlQuery extends Factory implements Rows{
        private $tableOrview;
        /**
         * constructor
         *
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function __construct($tableOrview) {
           parent::__construct();
           $this->tableOrview = $tableOrview;
        }
        /**
         * 取得表或视图第一行或某列数据
         *
         * @param [type] $column
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function first($column = null) {
            return $this->limit(1,$column)[0];
        }
        /**
         * 取得限制行数的row
         *
         * @param integer $row
         * @param [type] $column
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function limit(int $row,$column = null) {
            $query;
            if (is_null($column)) {
                $query = "SELECT * FROM $this->tableOrview LIMIT $row";
            } else if (is_array($column)) {
                foreach ($column as $k => $v) {
                    $column[$k] = '"' . $v . '"';
                }
                $str = implode(",",$column);
                $query = "SELECT $str FROM $this->tableOrview LIMIT $row";
            } else {
                $query = "SELECT \"$column\" FROM $this->tableOrview LIMIT $row";
            }
            $result = pg_query(parent::$connection->connection,$query);
            if ($result === FALSE) {
                return false;
            }
            $arr = pg_fetch_all($result);
            if ($arr === FALSE) {
                return false;
            }
            return $arr;
        }
    }
?>