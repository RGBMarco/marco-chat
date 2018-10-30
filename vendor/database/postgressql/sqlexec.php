<?php
    namespace Vendor\Database\Postgressql;
    require_once(__DIR__."/factory.php");
    class SqlExec extends Factory {
        private static $stmtArr;
        public function __construct() {
            parent::__construct();
            self::$stmtArr = array();
        }
        /**
         * 准备执行
         *
         * @param [type] $stmtName
         * @param [type] $cmd
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function sqlPrepare(string $stmtName,string $cmd) {
            if (array_key_exists($stmtName,self::$stmtArr)) {
                return false;
            }
            $resource = pg_prepare(parent::$connection->connection,$stmtName,$cmd);
            if ($resource === FALSE) {
                return false;
            }
            self::$stmtArr[$stmtName] = $resource;
            return true;
        }
        /**
         * 开始执行
         *
         * @param string $stmtName
         * @param array $params
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function sqlExec(string $stmtName,array $params) {
            $result = pg_execute(parent::$connection->connection,$stmtName,$params);
            if ($result === FALSE) {
                return false;
            } else {
                $ret = pg_fetch_all($result);
                return $ret;
            }
        }
        /**
         * 取消执行
         *
         * @param [type] $stmtName
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function sqlCanel($stmtName) {
            if (array_key_exists($stmtName,self::$stmtArr)) {
                $query = "DEALLOCATE \"$stmtName\"";
                pg_query($query);
                unset(self::$stmtArr[$stmtName]);
                return true;
            }
            return false;
        }
    }
?>