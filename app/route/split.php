<?php
    namespace App\Route;
    require_once(__DIR__."/exceptions/route.php");
    require_once(__DIR__."/spell.php");
    require_once(__DIR__."/register.php");
    use App\Route\Exceptions\RouteException;
    use App\Route\RouteSpell;
    use App\Route\RouteRegister;
    class RouteSplit {
        private $realUri;
        private $args;
        private $route_;
        public function __construct(string $uri) {
            $this->args = [];
            if ($uri === "/") {
                $this->realUri = "/";
                $this->route_ = \App\Route\RouteRegister::getRoute($this->realUri);
                return;
            }
            if ($uri[strlen($uri) - 1] === "/") {
                $uri = substr($uri,0,strlen($uri) - 1);
            }
            $regex = "#\/(?P<sub>[a-zA-Z0-9\.]+)#";
            $arr = [];
            $views = \App\Route\RouteRegister::routeRealViews();
            if (preg_match_all($regex,$uri,$arr)) {
                if (!array_key_exists("sub",$arr)) {
                    throw new RouteException("parse error");
                }
                $narr = $arr["sub"];
                while (count($narr) > 0) {
                    $v = "/" . implode('/',$narr);
                    if (in_array($v,$views)) {
                        $this->realUri = $v;
                        echo $this->realUri;
                        $this->route_ = \App\Route\RouteRegister::getRoute($this->realUri);
                        return;
                    } else {
                        array_unshift($this->args,array_pop($narr));
                    }
                }
            }
        }
        public function getRoute() {
            return $this->route_;
        }
        public function args():array {
            return $this->args;
        }
    }
?>