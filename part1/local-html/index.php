<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 09/12/2019
 * Time: 19:24
 */
function autoloadClasses($className) {
    $filename = "./../classes/" . $className . ".php";
    if (is_readable($filename)) {
        include $filename;
    }
}

spl_autoload_register("autoloadClasses");
require_once ('./../config/setenv.php');
/**$navItems = Array("home"=>"index.php","other page"=>"docs.php","more"=>"more.php");
$page = new WebPageWithNav('Home','Welcome', $navItems, 'footer');
echo $page->getPage();

 */

$navItems = Array("home"=>"/","documentation"=>"documentation", "about"=>"about");
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
//echo $path;
$base = "/";
if (strpos($path,$base)){
    $path = substr($path,strlen($base));
}
$options = array('action' => "",'subject' => "", 'param1' => "");

$path = explode("/", $path);

if (isset($path[0])) {
    $options['action'] = $path[0];

    if (isset($path[1])) {
        $options['subject'] = $path[1];

        if (isset($path[2])) {
            $options['param1'] = $path[2];
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
        $page->addApi('div test','words','wvwtjyw8yky8w 7krwe');
        echo $page->getPage();
        break;
    case 'about':
        $page = new WebPageWithNav('about','about', $navItems, 'footer');
        echo $page->getPage();
        break;
    case 'api';
        header("Content-type: applicaton/json");
        switch ($options['param1']){
            case 'schedule';
                $response = new JSONRecordSet();
                $sql = "SELECT sessions.id, title, slotsID FROM sessions";
                $response = $response->getJSONRecordSet($sql);
                echo $response;

                break;
            case 'presentations':
                $response = new JSONRecordSet();
                $sql = "Select from ";
                $response = $response->getJSONRecordSet($sql);
                echo $response;
            break;
            default:
                //header("Content-type: applicaton/json", true, 404);
                echo json_encode([
                    "status"=> "error",
                    "message"=> "endpoint not found"
                ]);
        }
        break;
    default:
        echo "404";
        break;
}
