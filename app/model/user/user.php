<?php
    namespace App\Model\User;
    require_once(__DIR__."/../../__autoload.php");
    require_once(__DIR__."/../../validate/user.php");
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/../../../vendor/database/postgressql/sqlquery.php");
    require_once(__DIR__."/../../utils/mail.php");
    require_once(__DIR__."/../../error/forbidden.php");
    require_once(__DIR__."/../../session/usersession.php");

    use App\Route\RouteHandler;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Validate\UserValidate;
    use Vendor\Database\Postgressql\SqlQuery;
    use App\Utils\Mail;
    use App\Error\Forbidden;
    use App\Session\UserSession;

    class User extends RouteHandler {
        use UserValidate;
        private $email_;
        private $name_;
        private $id_;
        private $password_;
        private $last_ip_;
        private $create_time_;
        private $last_time_;
        private $actived_;
        private $remebered_;
        private $remember_token_;
        private const REMEMBER_COOKIE_KEY = "remember_key";
        public function __construct() {

        }
        //用户登录
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
           if (!isset($request->get["e"]) || !isset($request->get["p"]) || !isset($request->get["r"])) {
                $forbidden = new Forbidden();
                return $forbidden->get($request,$response,[]);                
           }
           $email = $request->get["e"];
           $pw = $request->get["p"];
           $remember = $request->get["r"];
           //echo "remember_token :" . $request->cookie[self::REMEMBER_COOKIE_KEY];
           echo $email . " " .$pw . " " .$remember;
           $valid = $this->isValidEmail($email) && $this->isValidPassword($pw);
           $canlogin = $this->canLogin($email,$pw);
           if ($valid && $canlogin) {
                $remember_token = null;
                if ($remember === "true") {
                   $remember_token = md5(uniqid(microtime(true),true));
                   $this->rememberLogin($email,$remember_token);
                   $response->cookie($this->REMEMBER_COOKIE_KEY,$remember_token,0,"/user");
                }
                $id = $this->getUserId($email);
                $skip = parent::BASEURL ."/chat/$id";
                $data = [
                    'success'   => true,
                    'url'       => $skip,
                    'id'        => $id,
                    'email'     => $email,
                ];
                $userSession = new UserSession();
                $args = [
                    'email' => $email,
                    'id'    => $id,
                ];
                $userSession->get($request,$response,$args);
                $json = \json_encode($data);
                $response->end($json);
           } else {
               $data = [
                    'success'   => false,
               ];
               $json = \json_encode($data);
               $response->end($json);
           }
        }

        public function canLogin(string $e,string $p):bool {
            $sq = new SqlQuery(null);
            $q = "SELECT canLogin('$e','$p')";
            $result = $sq->query($q);
            if ($result['success']) {
                if ($result['data'][0]['canlogin'] === 't') {
                    return true;
                }  
            }
            return false;
        }
        public function rememberLogin(string $e,string $token) {
            $q = "SELECT rememberLogin('$e','$token')";
            $sq = new SqlQuery(null);
            $sq->query($q);
        }
        public function updateLogin(string $email,string $li) {
            $q = "SELECT updateLogin('$email','$li')";
            $sq = new SqlQuery(null);
            $sq->query($q);
        }
        //得到用户Id
        public function getUserId(string $email):int {
            $q = "SELECT getUserId('$email')";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            if ($result['success']) {
                return (int)($result['data'][0]['getuserid']);
            }
            return -1;
        }
        //用户注册
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {
            $response->header("Content-Type","application/json; charset=utf-8");
            $data = \json_decode($request->rawContent());
            $email = $data->email;
            $password = $data->password;
            if ($this->isValidEmail($email) && $this->isValidPassword($password)) {
                $active_token = uniqid(microtime(true),true);
                $actived = $this->isActivedUser($email);
                $url = parent::BASEURL . '/active?t=' . $active_token . "&e=$email";
                var_dump($actived);
                if ($actived) {
                    $d = array('success' => false);
                    $str = \json_encode($d);
                    $response->end($str);
                    return;
                } else {
                    $signup = $this->signupUser($email,$password,$active_token);
                    if (!$signup) {
                        $d = array('success' => false);
                        $str = \json_encode($d);
                        $response->header("Content-Type","application/json; charset=utf-8");
                        $response->end($str);
                        return;
                    }
                    $skip = parent::BASEURL . "/register";
                    $mail = new Mail();
                    $content = "<span>账户激活链接 </span><a href=$url>$url</a>";
                    $mail->sendMail('账户激活',$content,array($email));
                    $d = array('success' => true,'url' => $skip);
                    $str = \json_encode($d);
                    $response->end($str);
                }
            } else {
                //Bad Request
                $response->status(400);
                $response->end();
            }
        }
        public function isActivedUser($email):bool {
            $sq = new SqlQuery(null);
            $r1 = $sq->query("SELECT isActivedUser('$email')");
            if ($r1['success']) {
                if (isset($r1['data'])) {
                    $data = $r1['data'];
                    print_r($data);
                    if ($data[0]['isactiveduser'] === 't') {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
            return false;
        }
        public function signupUser(string $email,string $pw,string $token):bool {
            $sq = new SqlQuery(null);
            $ret = $sq->query("SELECT signupUser('$email','$pw','$token')");
            if ($ret['success']) {
                return true;
            }
            return false;
        }
        public function __destruct() {

        }
    }
?>