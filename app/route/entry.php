<?php
    namespace App\Route;
    require_once(__DIR__."/split.php");
    require_once(__DIR__."/exceptions/route.php");
    use App\Route\Exceptions\RouteException;
    use App\Route\RouteSplit;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    class RouteEntry {
        public $method_;
        public $class_;
        public $args_;
        public function __construct($uri,$method = "get") {
            $rs = new RouteSplit($uri);
            $this->method_ = $method;
            $spell = $rs->getRoute();
            if ($spell === null)
                throw new RouteException("have not fit exception");
            $this->class_ = $spell->execClass();
            $argsName = $spell->argsName();
            $args = $rs->args();
            $this->args_ = [];
            foreach ($argsName as $k => $v) {
                $this->args_[$k] = $args[$k]; 
            }
        }
        public function run(swoole_http_request $request,swoole_http_response $response) {
            var_dump($this->class_);
            $obj = new $this->class_();
            $method = $this->method_;
            $obj->$method($request,$response,$this->args_);
        }
    }
?>