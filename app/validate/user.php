<?php
    namespace App\Validate;
    trait UserValidate {
        function isEmail(string $email):bool {
            $regex = "#^(([0-9a-zA-Z-_]+)@([0-9a-z]+){2,3})((\.[a-z]{2,3})+)$#";
            if (preg_match($regex,$email)) {
                echo "验证成功: " . $email . "\n";
                return true;
            } else {
                echo "验证失败： " . $email . "\n";
                return false;
            }
        }
        function isValidName(string $name):bool {
            return count($name) > 15 ? false : true;
        }
        function isValidPassword(string $password):bool {
            $regex = "#^[0-9a-zA-Z]{6,50}$#";
            if (preg_match($regex,$password)) {
                return true;
            } else {
                return false;
            }
        }

    }
    /*class user {
        use UserValidate;
        public function __construct() {
            $email = "5774829 75-@qq.com.cn";
            $this->isEmail($email);
            $password = "447711..";
            if ($this->isValidPassword($password)) {
                echo "this is valid password!\n";
            } else {
                echo "is not valid password!\n";
            }
        }
    }
    //use UserValidate;
    $user = new user();*/
?>