<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 09/12/2019
 * Time: 19:24
 */
function autoloadClasses($className) {
    $filename = "./classes/" . $className . ".php";
    if (is_readable($filename)) {
        include $filename;
    }
}

spl_autoload_register("autoloadClasses");
require_once('./config/setenv.php');
/**$navItems = Array("home"=>"index.php","other page"=>"docs.php","more"=>"more.php");
$page = new WebPageWithNav('Home','Welcome', $navItems, 'footer');
echo $page->getPage();

 */

$navItems = Array("home"=>"/","documentation"=>"documentation", "about"=>"about");
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
//echo $path."<br>\n";
$base = "WAI-assigin/part1/";
if (strpos($path,$base)){
    $path = substr($path,strlen($base));
}
$options = array('action' => "",'subject' => "", 'param1' => "");

$path = explode("/", $path);
//echo CONFIGLOCATION;
if (isset($path[0])) {
    $options['action'] = $path[0];

    if (isset($path[1])) {
        $options['subject'] = $path[1];

        if (isset($path[2])) {
            $options['param1'] = $path[2];

            if (isset($path[3])) {
                $options{'param2'} = $path[3];

                if (isset( $path[4])){
                    $options['param3'] = $path[4];
                }
            }
        }
    }
}

switch ($options['subject']){
    case '':
        $page = new WebPageWithNav('Home','Welcome', $navItems, 'footer');
        echo $page->getPage();
        break;
    case 'documentation':
        $page = new SectionedWebpage('page2', 'second page', $navItems, 'footer');
        $page->addToBody("This page shows a 2nd page");
        $page->addApi("http://localhost/api/", "Main api entry point", "This give details regarding the api" );
        $page->addApi("http://localhost/api/schedule", "Schedule information", "This gives the schedule information for the conference" );
        $page->addApi("http://localhost/api/schedule/:session", "Session information", "This gives detailed information regarding the session");
        $page->addApi("http://localhost/api/presentations/", "Presentations for the conference", "This gives details regarding the listed presentations within the conference");
        $page->addApi("http://localhost/api/presentations/search/:searchterm", "Search function", "This function returns the presentations that contain a given search term in the title or abstract ");
        $page->addApi("http://localhost/api/presentations/category/:categoryname", "Category search", "This will return the presentations which contain the given search term in the title or abstract, within a selected category");
        $page->addApi("http://localhost/api/login/Param1/Param2","login","Login API 
        Where Param1 is username and param2 is password");
        echo $page->getPage();
        break;
    case 'about':
        $page = new WebPageWithNav('about','about', $navItems, 'footer');
        echo $page->getPage();
        break;
    case 'api';
        if ((($_SERVER['REQUEST_METHOD'] == 'POST') ||
                ($_SERVER['REQUEST_METHOD'] == 'PUT') ||
                ($_SERVER['REQUEST_METHOD'] == 'DELETE')) &&
            (strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false)) {

        }
        $input = json_decode(file_get_contents('php://input'), true);
        $data = isset($input['data']) ? $input['data'] : null;

        //header("Content-type: applicaton/json");
        header("Access-Control-Allow-Origin: *");
        switch ($options['param1']){
            case 'schedule';
                $response = new JSONRecordSet();

                if (isset($options['param2'])) {

//                    $data = json_decode($data);
                    $sql = "SELECT slots.day, sessions.id, sessions.title, sessions.room, slotsID, slots.time, sessions.chair FROM sessions JOIN slots ON
slotsID = slots.id WHERE slots.day = :day ORDER BY slots.time";
                    $response = $response->getJSONRecordSet($sql, array("day" => $options['param2']));
                }
                else{
                    $sql = "SELECT slots.day, sessions.id, sessions.title, sessions.room, slotsID, slots.time, sessions.chair FROM sessions JOIN slots ON
slotsID = slots.id ORDER BY slots.time";
                    $response = $response->getJSONRecordSet($sql);
                }
                echo $response;
                break;
            case 'slot';
                $response = new JSONRecordSet();
                $sql = "SELECT slots.day, sessions.id, sessions.title, activities.abstract, sessions.room, slotsID, slots.time, sessions.chair FROM sessions JOIN slots ON
slotsID = slots.id join activities ON sessions.id = activities.sessionsID WHERE sessions.id = :id";
                $response = $response->getJSONRecordSet($sql, array("id" => $options['param2']));

                echo $response;

            break;
            case 'papers';
                $response = new JSONRecordSet();
                $sql = "SELECT sessions.id, sessions.title, slotsID FROM sessions";
                $response = $response->getJSONRecordSet($sql);
                echo $response;
                break;
            case 'session':
                $response = new JSONRecordSet();
                $sql = "Select * from sessions WHERE sessions.id = :id";
                $response= $response->getJSONRecordSet($sql, array("id"=>$options['param2']));
                echo $response;
                break;
            case 'login';
                if(true) {
                    $user = $data["user"];
                    $pass = $data["pass"];
                    $sql = "SELECT password, admin FROM users WHERE username = :email";
                    $response = new JSONRecordSet();

                    $response = $response->getJSONRecordSet($sql, array("email" => $user));
                    $dataResponse = json_decode($response, 1);
                    // echo $data["status"];
                    // Check if no errors returned
                    if ($dataResponse["status"] !== "error") {
                        $dataResponse = $dataResponse["data"]["Results"][0];
                        //echo $data["password"];
                        if (password_verify($pass, $dataResponse["password"])) {
                            $token = array();
                            $token['user'] = $user;
                            $token['admin'] = $dataResponse["admin"];

                            $encodedToken = JWT::encode($token, ApplicationRegistry::getJWTjey());

                            http_response_code(201);
                            echo json_encode(array("message" => "logged in", "token" => $encodedToken));
                        } else {
                            echo json_encode(array("message" => "unknown user/password"));

                        }
                    } else {
                        echo json_encode(array("message" => "unknown user/password"));
                    }
                }
                else{
                    echo json_encode(array("message" => "username or  password not set"));
                }
                break;
            case 'days':

                $response = new JSONRecordSet();
                $sql = "SELECT DISTINCT slots.day FROM slots";
                $response = $response->getJSONRecordSet($sql);
                echo $response;
                break;
            case 'update':
                $token = $data["token"];
                $id = $data["id"];
                $newChair = $data["chair"];

                $tokenDecoded = JWT::decode($token, ApplicationRegistry::getJWTjey());
                if ($tokenDecoded->admin == true){
                    $sql = "UPDATE sessions SET chair = :chair WHERE id = :id";

                    $params = array(":chair"=>$newChair, ":id"=>$id);
                    $dbConn = pdoDB::getConnection();

                    $queryResult = $dbConn->prepare($sql);
                    $success = $queryResult->execute($params);

                    // $wasupdated will tell us if anything was updated
                    $wasupdated = ($queryResult->rowCount() > 0 ? true : false);


                    $dbConn = null;

                    http_response_code(201);
                    echo json_encode(array("message" => "database updated", "success"=>$success, "updated"=>$wasupdated));

                } else {
                    http_response_code(403);
                    echo json_encode(array("message" => "authentiaction required", "success"=>false));
                }


                break;



            default:
                //header("Content-type: applicaton/json", true, 404);
                echo json_encode([
                    "status"=> "error",
                    "message"=> "endpoint not found"
                ]);
        }//end api case
        break;
    default:
        echo "404";
        break;
}
