<?php 
require_once 'vendor/autoload.php';

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

define("FROM_EMAIL","arparikh1010@gmail.com");
global $db;

try{
    $db = new db(DB_HOST,DB_USER,DB_PASSWORD,DB_DATABASE);

}catch(Exception $e){   
    echo 'Database connect error : ',  $e->getMessage(), "\n";
    exit;
}