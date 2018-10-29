<?php
    class DbConfig {
        public $config = [
            'postgres' =>   [
                'host'      =>  '127.0.0.1',
                'dbname'    =>  'postgres',
                'user'      =>  'postgres',
                'password'  =>  '577482975',
            ],
        ];
        public function parseURL($dbKind):string {
            $ret = "";
            if (array_key_exists($dbKind,$config)) {
                foreach($config[$dbKind] as $k => $v) {
                    $ret .= $k . $v;
                }
            } else {
                $ret = "";
            }
            return $ret;
        }
    }
?>