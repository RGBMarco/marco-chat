<?php
    namespace App\Route;
    require_once(__DIR__."/exceptions/route.php");
    use App\Route\Exceptions\RouteException;
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
            if (class_exists($route,false)) {
                throw new RouteException("routehandler class is not exists");
                return false;
            } else {
                if (is_subclass_of($route,'App\Route\RouteHandler')) {
                    if (array_key_exists($view,self::$routes)) {
                        throw new RouteException("view alreadly have handler");
                        return false;
                    } else {
                        self::$routes[$view] = $route;
                        return false;
                    }
                } else {
                    throw new RouteException("gived is not routehandler");
                    return false;
                }
            }
            return true;
        }
    }
?>