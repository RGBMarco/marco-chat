<?php
    namespace Cache\Exceptions;
    require_once(__DIR__."/../../utils/colorful.php");
    use Utils\Colorful;
    class CacheException extends \Exception{
        private $msg_;
        public function __construct($msg) {
            $this->msg_ = $msg;
        }
        public function __toString() {
            $str = "Cahce Exception: " . Colorful::getDangerString($this->msg_) . " in " . Colorful::getInfoString($this->getFile()) . " at " . Colorful::getDangerString($this->getLine()) . " at " . Colorful::getDangerString(date("Y-m-d h:i:s"));
            return $str;
        }
    }
?>