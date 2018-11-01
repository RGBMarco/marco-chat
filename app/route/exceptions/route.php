<?php
    namespace App\Route\Exceptions;
    require_once(__DIR__."/../../../utils/colorful.php");
    use \Utils\Colorful;
    class RouteException extends \Exception {
        private $reason;
        public function __construct($reason) {
            $this->reason = $reason;
        }
        public function __toString() {
            return "Route Exception: " . Colorful::getDangerString($this->reason) . " in " . Colorful::getInfoString($this->getFile()) . " on " . Colorful::getInfoString($this->getLine()) . " at " . date("Y-m-d H:i:s");
        }
    }
?>