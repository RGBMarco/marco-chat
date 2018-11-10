<?php
    namespace Database\Factory;
    require_once(__DIR__."/../migrations/migrateable.php");
    use Database\Migrations\Immediate;
    //生成已注册用户假数据 //同时产生用户信息
    class User {
        use Immediate;
        public function __construct() {

        }
        public function produce(int $count) {
            for ($i = 0; $i < $count; ++$i) {
                $email = $this->randEmail();
                $password = md5("123456");
                $cmd = "
                    INSERT INTO users(email,password,actived) VALUES('$email','$password',true);
                ";
                $this->immediateExec("produce$i",$cmd);
            }
        }
        public function randEmail():string {
            $r = rand(1,30000);
            $pre = time() + $r;
            return "$pre@qq.com";
        }
    }
    $p = new User();
    $p->produce(20);
?>