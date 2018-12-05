<?php
    namespace App\Model\User;
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/util.php");
    require_once(__DIR__."/../../error/forbidden.php");
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Model\Error\Forbidden;
    use App\Route\RouteHandler;
    class UserInfo extends RouteHandler{
        use Util\UserInfo;
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (!isset($args['id'])) {
                $forbidden = new Forbidden();
                return $forbidden->get($request,$response,$args);
            }
            $userinfo = $this->getUserInfoAllById((int)$args['id']);
            $success = $this->parseUserInfo($userinfo);
            $data = [
                'success'   => $success,
                'data'      => $userinfo,
            ];
            $str = \json_encode($data);
            $response->header("Content-Type","application/json");
            $response->end($str);
        }
        public function post(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (!isset($args['id'])) {
                $forbidden = new Forbidden();
                return $forbidden->get($request,$response,$args);
            }
            $id = $args['id'];
            $data = \json_decode($request->rawContent());
            $check = $this->checkUserInfo($data);
            $canUp = $this->canUpdate($check);
            $havePhoto = 'f';
            if (isset($data->photoAbout)) {
                $havePhoto = 't';
                $data->photo = \base64_decode($data->photo);
                $data->photo = \pg_escape_bytea($data->photo);
                $data->photoType = $data->photoAbout->type;
            } else {
                $data->photoType = "";
            }
            $data->havePhoto = $havePhoto;
            if ($data->birth === "未填写") {
                $data->isRealDate = 'f';
            } else {
                $data->isRealDate = 't';
            }
            $success = false;
            if ($canUp) {
                $success = $this->updateUserInfo($id,$data);
            }
            $send = [
                'hint'      =>  $this->checkUserInfo($data),
                'success'   =>  $success,
            ];
            $sendStr = \json_encode($send);
            $response->end($sendStr);
        }
        public function getValidUserInfo(array $user) {
            return isset($user['email']) && isset($user['name']) && isset($user['sex']);
        }
        public function parseUserInfo(array &$user):bool {
            $success = $this->getValidUserInfo($user);
            foreach ($user as $k => $v) {
                if (!isset($user[$k])) {
                    $user[$k] = "未填写";
                }
            }
            return $success;
        }
        public function checkUserInfo(&$obj) {
            $success = [
                'name'      =>  $this->isValidName($obj->name),
                'photo'     =>  $this->isValidPhoto($obj->photo,isset($obj->photoAbout),(isset($obj->photoAbout) ? $obj->photoAbout : null)),
                'sign'      =>  $this->isValidSign($obj->sign),
                'sex'       =>  $this->isValidSex($obj->sex),
                'birth'     =>  $this->isValidBirth($obj->birth),
                'vocation'  =>  $this->isValidVocation($obj->vocation),
                'hometown'  =>  $this->isValidHometown($obj->hometown),
                'zone'      =>  $this->isValidZone($obj->zone),
                'company'   =>  $this->isValidCompany($obj->company),
                'school'    =>  $this->isValidSchool($obj->school),
            ];
            return $success;
        }
        public function canUpdate($success) {
            foreach ($success as $k => $v) {
                if (!$v) {
                    return false;
                }               
            }
            return true;
        }
    }
?>