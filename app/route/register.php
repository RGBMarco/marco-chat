<?php
    namespace App\Route;
    require_once(__DIR__."/exceptions/route.php");
    require_once(__DIR__."/spell.php");
    use App\Route\Exceptions\RouteException;
    use App\Route\RouteSpell;
    /**
     * 支持GET POST PATCH DELETE
     *
     * @author: Marco
     * @email: rgbmarco@gmail.com
     * @date: 2018
     */
    class RouteRegister {
        public static $routes = array();
        public function __construct() {

        }
        public function routeExists($view,$route) {
            $rs  = new \App\Route\RouteSpell($view,$route);
            foreach (self::$routes as $v => $k) {
                if ($rs->realView() === $k->realView()) {
                    return true;
                }
            }
            return false;
        }
        public static function routeRealViews() {
            $realViews = [];
            foreach (self::$routes as $k => $v) {
                array_push($realViews,$v->realView());
            }
            return $realViews;
        }
        public static function getRoute($realview) {
            foreach (self::$routes as $k => $v) {
                if ($v->realView() === $realview) {
                    return $v;
                }
            }
            return null;
        }
        /**
         * 添加路由规则
         *
         * @param string $view
         * @param string $route
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function add(string $view,string $route) {
            if (self::routeExists($view,$route)) {
                throw new RouteException("route exist");
            }
            $rs = new \App\Route\RouteSpell($view,$route);
            self::$routes[$view] = $rs;
        }
    }
?>