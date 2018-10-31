<?php
    namespace Vendor\Database\Postgressql;
    require_once(__DIR__."/../../../config/dbconfig.php");
    require_once(__DIR__."/../exceptions/connect.php");
    use Config\DbConfig;
    use Vendor\Database\Exceptions\ConnectException;
    class Connection {
        private const DBKIND = 'postgres';
        public $connection;
        private $connected;
        /**
         * 连接postgressql数据库
         *
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function __construct() {
            $this->connection = pg_pconnect(DbConfig::parseURL(self::DBKIND));
            if ($this->connection === FALSE) {
                $this->connected = false;
                throw new ConnectException("Can't Connect",DbConfig::parseURL(self::DBKIND));
            } else {
                $this->connected = true;
            }
        }
        /**
         * 判断数据库是否已连接
         *
         * @return boolean
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function isConnected() {
            if ($this->connected) {
                if (pg_connection_status($this->connection) === PGSQL_CONNECTION_OK) {
                    $this->connected = true;
                } else {
                    $this->connected = false;
                }
            }
            return $this->connected;
        }
        /**
         * 析构函数 断开连接
         *
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function __destruct() {
            
        }
    }
?>