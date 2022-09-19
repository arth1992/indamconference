<?php 
session_start();
require_once 'vendor/autoload.php';
use Rakit\Validation\Validator;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$validator = new Validator;

require('db.php');

define('DB_HOST','localhost');
define('DB_USER',$_ENV['DB_USER']);
define('DB_PASSWORD',$_ENV['DB_PASSWORD']);
define('DB_DATABASE',$_ENV['DB_DATABASE']);

define("CCA_MERCHANT_ID", $_ENV['CCA_MERCHANT_ID']);
define("CCA_ACCESS_CODE", $_ENV['CCA_ACCESS_CODE']);
define("CCA_WORKING_KEY", $_ENV['CCA_WORKING_KEY']);

define("EARLY_BIRD","2022-09-25");

define("FROM_EMAIL","arparikh1010@gmail.com");
global $db;

global $allowed_nationality;
$allowed_nationality = array("saarc","other");

global $allowed_category;
$allowed_category = array("academician","student","other");


if($_ENV['ENVIRONMENT'] == "development"){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

try{
    $db = new db(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);

}catch(Exception $e){   
    echo 'Database connect error : ',  $e->getMessage(), "\n";
    exit;
}

# sanitize form data
function input_cleaner($data)
{
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $data = trim($data);
    return $data;
}

function get_price_details($nationality,$category,$is_member = false){

    $currency = "INR";
    $amount = 0;
    switch ($nationality) {
        
        case "saarc" : 
            if($category == "student") : 
                if($is_member) {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 2400;
                    }else{
                        $amount = 3200;
                    }
                }else {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 3000;
                    }else{
                        $amount = 4000;
                    }
                }
            else : 

                if($is_member) {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 4800;
                    }else{
                        $amount = 5600;
                    }
                }else {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 6000;
                    }else{
                        $amount = 7000;
                    }
                }
            endif;

        break;
        
        default : 
            if($category == "student") : 
                if($is_member) {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 80;
                    }else{
                        $amount = 120;
                    }
                }else {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 100;
                    }else{
                        $amount = 150;
                    }
                }
            else : 

                if($is_member) {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 240;
                    }else{
                        $amount = 280;
                    }
                }else {
                    if(date('Y-m-d') <= EARLY_BIRD){
                        $amount = 300;
                    }else{
                        $amount = 350;
                    }
                }
                
            endif;
            $currency = "USD";
        break;

    }
    return array($amount,$currency);
}
 
?>