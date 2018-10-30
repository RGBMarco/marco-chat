<?php
    namespace Utils;
    class Colorful {
        private static $colors = array(
            'white'     =>  "\033[37m",
            'black'     =>  "\033[30m",
            'red'       =>  "\033[31m",
            'green'     =>  "\033[32m",
            'blue'      =>  "\033[34m",
            'purle'     =>  "\033[35m",
            'cyan'      =>  "\033[36m",
        );
        private const RECOVER = "\033[0m";
        /**
         * constructor
         *
         * @return void
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public function __construct() {

        }
        /**
         * 得到带颜色的字符串
         *
         * @return string
         * @author: Marco
         * @email: rgbmarco@gmail.com
         * @date: 2018
         */
        public static function getColorfulString($str,$color):string {
            if (array_key_exists($color,self::$colors)) {
                return self::$colors[$color] . $str . self::RECOVER;
            } else {
                return $str;
            }
        }
    }
?>