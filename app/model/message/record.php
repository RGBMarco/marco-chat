<?php
    namespace App\Model\Message;
    require_once(__DIR__."/../../route/handler.php");
    require_once(__DIR__."/../../error/forbidden.php");
    require_once(__DIR__."/../../session/usersession.php");
    require_once(__DIR__."/../../../vendor/database/postgressql/sqlquery.php");
    require_once(__DIR__."/../user/util.php");
    require_once(__DIR__."/type.php");
    use App\Error\Forbidden;
    use App\Session\UserSession;
    use App\Route\RouteHandler;
    use Vendor\Database\Postgressql\SqlQuery;
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Model\User\Util\UserInfo;
    use App\Model\Message\Record_t;
    class MessageRecord extends RouteHandler {
        use UserInfo;
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            $forbidden = new Forbidden();
            if (!isset($args['id'])) {
                $forbidden->get($request,$response,$args);
                return;
            }
            $userSession = new UserSession();
            if (!$userSession->post($request,$response,$args)) {
                $forbidden->get($request,$response,$args);
                return;
            }
            $id = $args['id'];
            //var_dump($this->getSessionsRecords($id);
            $data = array(
                'success' => true,
                'data'    => [
                    'records'   => $this->getSessionsRecords($id),
                    'sessions'  => $this->getSingleSessionsMessages($id),
                ],
            );
            $dataStr = \json_encode($data);
            $response->header("Content-Type","application/json");
            $response->end($dataStr);
            return;
        }
        public function getRelateSingleSessions(string $id) {
            $uid = (int)($id);
            $q = "SELECT getRelateSingleSession($id)";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            $jsonArr = [];
            if ($result['success']) {
                $tempArr = $result['data'];
                foreach ($tempArr as $k => $v) {
                    array_push($jsonArr,$v['getrelatesinglesession']);
                }
            }
            return $jsonArr;
        }

        public function getSingleSessionsMessages($id) {
            $ret = $this->getRelateSingleSessions($id);
            $sessions = [];
            foreach ($ret as $k => $v) {
                $messages = \json_decode($v,true);
                foreach ($messages as $index => $value) {
                    $firstName = $this->getUserNameById($value["firstId"]);
                    $secondName = $this->getUserNameById($value["secondId"]);
                    $value["firstName"] = $firstName;
                    $value["secondName"] = $secondName;
                    $messages[$index] = $value;
                }
                $sessions[$k] = $messages;
            }
            return $sessions;
        }

        public function getSessionsRecords($id) {
            $q = "SELECT getSessionRecord($id)";
            $sq = new SqlQuery(null);
            $result = $sq->query($q);
            $ret = [];
            if ($result['success']) {
                $res = $result['data'];
                foreach ($res as $k => $v) {
                    $obj = \json_decode($v['getsessionrecord'],true);
                    $firstName = $this->getUserNameById($obj["firstId"]);
                    $secondName = $this->getUserNameById($obj["secondId"]);
                    $obj["firstName"] = $firstName;
                    $obj["secondName"] = $secondName;
                    $ret[$k] = $obj;
                }
            }
            return $ret;
        }
    }
?>