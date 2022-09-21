<?php
session_start();
require_once 'vendor/autoload.php';

use Rakit\Validation\Validator;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$validator = new Validator;

require('db.php');

define('DB_HOST', 'localhost');
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);
define('DB_DATABASE', $_ENV['DB_DATABASE']);

define("CCA_MERCHANT_ID", $_ENV['CCA_MERCHANT_ID']);
define("CCA_ACCESS_CODE", $_ENV['CCA_ACCESS_CODE']);
define("CCA_WORKING_KEY", $_ENV['CCA_WORKING_KEY']);

define("EARLY_BIRD", "2022-09-25");

define("FROM_EMAIL", "arparikh1010@gmail.com");
global $db;

global $allowed_nationality;
$allowed_nationality = array("saarc", "other");

global $allowed_category;
$allowed_category = array("academician", "student", "other");


if ($_ENV['ENVIRONMENT'] == "development") {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}else{
    error_reporting(0);
}

try {
    $db = new db(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
} catch (Exception $e) {
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

function get_price_details($nationality, $category, $is_member = false)
{
    
    $currency = "INR";
    $amount = 0;
    switch ($nationality) {

        case "saarc":
            if ($category == "student") :
                if ($is_member) {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 2400;
                    } else {
                        $amount = 3200;
                    }
                } else {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 3000;
                    } else {
                        $amount = 4000;
                    }
                }
            else :

                if ($is_member) {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 4800;
                    } else {
                        $amount = 5600;
                    }
                } else {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 6000;
                    } else {
                        $amount = 7000;
                    }
                }
            endif;

            break;

        default:
            if ($category == "student") :
                if ($is_member) {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 80;
                    } else {
                        $amount = 120;
                    }
                } else {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 100;
                    } else {
                        $amount = 150;
                    }
                }
            else :

                if ($is_member) {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 240;
                    } else {
                        $amount = 280;
                    }
                } else {
                    if (date('Y-m-d') <= EARLY_BIRD) {
                        $amount = 300;
                    } else {
                        $amount = 350;
                    }
                }

            endif;
            $currency = "USD";
            break;
    }
    return array($amount, $currency);
}


function usd_to_inr($usd_amount)
{
    global $db;
    $inr_amount = $usd_amount;
    $usd_inr_rate = 72.00; // Default value

    /*get latest reate from DB if available*/
    $exist_rate = $db->query('SELECT current_rate from currency_rate WHERE created_at  BETWEEN "'.date('Y-m-d 00:00:00').'" and "'.date('Y-m-d 23:59:59').'" ')->fetchArray();
    if (!empty($exist_rate)) {
        $usd_inr_rate = $exist_rate['current_rate'];
    } else {
        $api_key = $_ENV["CURRENCY_LAYER_KEY"];
        $endpoint = "http://api.currencylayer.com/live";
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, ['query' => [
            'access_key' => $api_key,
            'currencies' => 'INR',
            'source' => 'USD',
            'format' => 1,
        ]]);
        $statusCode = $response->getStatusCode();
        $content = json_decode($response->getBody(), true);
        if ($statusCode == 200 && isset($content['success']) && $content['success']) {
            $usd_inr_rate = isset($content['quotes']['USDINR']) ? number_format($content['quotes']['USDINR'], 2) : 0;
            $db->query('INSERT into currency_rate(currency_from,currency_to,current_rate)
                        VALUES(?,?,?)',array('USD','INR',$usd_inr_rate));
        }

    }

    $inr_amount = number_format($usd_inr_rate * $usd_amount, 2, '.', '');
    return array($usd_inr_rate,$inr_amount);  // current rate , final amount to be payable by user from usd
}
