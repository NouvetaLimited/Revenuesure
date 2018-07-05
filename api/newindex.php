<?php
/**
 * Created by PhpStorm.
 * User: langat
 * Date: 5/11/2018
 * Time: 8:47 AM
 */


include 'includes/DB.php';
include 'helpers/Response.php';
include 'helpers/ConfirmationCode.php';
include "helpers/AfricasTalkingGateway.php";
include 'helpers/fpdf/fpdf.php';

date_default_timezone_set("Africa/Nairobi");
header("Access-Control-Allow-Origin: *");

if(!empty($_POST['function']))
    $function = $_POST['function'];

if(!empty($_GET['function']))
    $function = $_GET['function'];

function getReceipt(){
    $today = date("d");
    $rand = strtoupper(substr(uniqid(sha1(time())),0,4));

    return $unique = $today . $rand;
}
$Transaction_code = getReceipt();
$TransactionDesc="REVENUESURE";


