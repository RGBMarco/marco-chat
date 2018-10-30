<?php
    namespace Vendor\Database\Exceptions;
    require_once(__DIR__."/../../../utils/colorful.php");
    use Utils\Colorful;
    class ConnectException extends \Exception {
        public $connectUrl;
        public $errorMsg;
        /**
         * 连接数据库失败异常
         *
         * @param [type] $message
         * @param string $curl
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function __construct($message,$curl="Unknown") {
            $this->connectUrl = $curl;
            $this->errorMsg = $message;
        }
        /**
         * 获得错误消息
         *
         * @return string
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function errorMessage():string {
            $str = "Connection Error:[URL: $this->connectUrl] [errorMsg: $this->errorMsg]\n";
            return Colorful::getColorfulString($str,'red'); 
        }
    }
?>