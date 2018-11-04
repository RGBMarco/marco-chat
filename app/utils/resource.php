<?php
    namespace App\Utils;
    require_once(__DIR__."/../route/handler.php");
    use Swoole\Http\Request as swoole_http_request;
    use Swoole\Http\Response as swoole_http_response;
    use App\Route\RouteHandler;
    class Resource extends RouteHandler {
        public function __construct() {

        }
        public function get(swoole_http_request $request,swoole_http_response $response,array $args) {
            if (array_key_exists("name",$args)) {
                $filename = $args["name"];
                $regex = "#(\.(?P<suffix>css|js|png))$#";
                $arr = [];
                if (preg_match_all($regex,$filename,$arr)) {
                    if (!$arr["suffix"][0]) {
                        $response->end(" ");
                    } else {
                        if ($arr["suffix"][0] === "js") {
                            $response->header("Content-Type","application/x-javascript");
                            $path = __DIR__."/../../public/" . $arr["suffix"][0] . "/" . $filename;
                            $size = filesize($path);
                            $handle = fopen($path,"r");
                            $response->end(fread($handle,$size));
                        } else if ($arr["suffix"][0] === "css") {
                            $response->header("Content-Type","text/css");
                            $path = __DIR__."/../../public/" . $arr["suffix"][0] . "/" . $filename;
                            $size = filesize($path);
                            $handle = fopen($path,"r");
                            $response->end(fread($handle,$size));
                        } else if ($arr["suffix"][0] === "png") {
                            $response->header("Content-Type","image/png");
                            $path = __DIR__."/../../public/img/" . $filename;
                            $size = filesize($path);
                            $handle = fopen($path,"r");
                            $response->end(fread($handle,$size));
                        } else {
                            $response->end(" ");
                        }
                    }
                } else {
                    $response->end(" ");
                }
            }
        }
    }
?>