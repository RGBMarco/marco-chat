<?php
    namespace App\Route;
    require_once(__DIR__."/exceptions/route.php");
    use App\Route\Exceptions\RouteException;
    class RouteSpell {
        private $realView_;
        private $argsName_;
        private $execClass_;
        public function __construct(string $view,string $execClass) {
            if (!class_exists($execClass,false)) {
                throw new RouteException("class is not exist");
            }
            if (!is_subclass_of($execClass,"\App\Route\RouteHandler")) {
                throw new RouteException("is not routehandler subclass");
            }
            $regex = "#\/(?P<sub>[a-zA-Z0-9]+)|\/\{(?P<args>[a-zA-Z0-9]+)\}#";
            $arr = [];
            $this->realView_ = "/";
            $this->argsName = [];
            $this->execClass_ = $execClass;
            if ($view === "/") {
                return;
            }
            if ($view[strlen($view)-1] === "/") {
                $view = substr($view,0,strlen($view) - 1);
            }
            if (preg_match_all($regex,$view,$arr)) {
                $this->realView_ = "";
                foreach ($arr["sub"] as $k => $v) {
                    $this->realView_ .= "/" . $v; 
                }
                foreach ($arr["args"] as $k => $v) {
                    $this->argsName_[$k] = $v;
                }
            }
        }
        public function realView():string {
            return $this->realView_;
        }
        public function argsName():array {
            return $this->argsName_;
        }
        public function execClass() {
            return $this->execClass_;
        }
    }
?>