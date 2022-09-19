<?php 
session_start(); 

require_once 'vendor/autoload.php';

use Rakit\Validation\Validator;
$validator = new Validator;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

require('db.php');

define('DB_HOST','localhost');
define('DB_USER',$_ENV['DB_USER']);
define('DB_PASSWORD',$_ENV['DB_PASSWORD']);
define('DB_DATABASE',$_ENV['DB_DATABASE']);

define("CCA_MERCHANT_ID", $_ENV['CCA_MERCHANT_ID']);
define("CCA_ACCESS_CODE", $_ENV['CCA_ACCESS_CODE']);
define("CCA_WORKING_KEY", $_ENV['CCA_WORKING_KEY']);
define("EARLY_BIRD_DATE", "2022-09-19");


if($_ENV['ENVIRONMENT'] == "development") : 
    error_reporting(E_ALL);
else :
    error_reporting(0);
endif;

global $db;
global $allowed_nationality;
$allowed_nationality = array("saarc","other");
global $allowed_category;
$allowed_category = array("academician","research_scholar");


try{
    $db = new db(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);
}catch(Exception $e){   
    echo 'Database connect error : ',  $e->getMessage(), "\n";
    exit;
}

//create our cleaner/validation function
function input_cleaner($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input);
    return $input;
  }

function get_price($nationality,$category){
    $early_bird = false;
    $price = 0;
    $currency = "INR";
    // first check if early bird is applied or not
    $current_date = date('Y-m-d');
    if($current_date <= EARLY_BIRD_DATE) {
        $early_bird = true;
    }

    switch ($nationality){
        case "saarc" :
             switch($category) {
                case "academician" : 
                    if($early_bird) : 
                      $price = 6000;
                    else :
                      $price = 7000;
                    endif;
                break;
                case "research_scholar": 
                    if($early_bird) : 
                        $price = 3000;
                      else :
                        $price = 4000;
                      endif;
                break;
             }
            break;
        default : 
            switch($category) {
                case "academician" : 
                    if($early_bird) : 
                    $price = 300;
                    else :
                    $price = 350;
                    endif;
                break;
                case "research_scholar": 
                    if($early_bird) : 
                        $price = 100;
                    else :
                        $price = 150;
                    endif;
                break;
            }
            $currency = "USD";
            break;
    }

    return array($price,$currency);

}