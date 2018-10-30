<?php
    namespace Config;
    class DbConfig {
        public static $config = [
            'postgres' =>   [
                'host'      =>  '127.0.0.1',
                'dbname'    =>  'phpstudy',
                'user'      =>  'postgres',
                'password'  =>  '577482975',
            ],
        ];
        public static function parseURL($dbKind):string {
            $ret = "";
            if (array_key_exists($dbKind,self::$config)) {
                foreach(self::$config[$dbKind] as $k => $v) {
                    $ret .= $k ."=" .$v ." ";
                }
            } else {
                $ret = "";
            }
            return $ret;
        }
    }
?>