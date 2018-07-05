<?php
/**
 * Created by PhpStorm.
 * User: AlexBoey
 * Date: 4/24/2017
 * Time: 5:55 PM
 */

include 'includes/DB.php';
include 'helpers/Response.php';
include 'helpers/ConfirmationCode.php';
include "helpers/AfricasTalkingGateway.php";
include 'helpers/fpdf/fpdf.php';

date_default_timezone_set("Africa/Nairobi");
header("Access-Control-Allow-Origin: *");

$ReceiptNo = $_POST['AccountReference'];
$phone_number = formatPhoneNumber( $_POST['PhoneNumber']);
$amount_paid = $_POST['Amount'];
$code = $_POST['MpesaReceiptNumber'];
$payment_method = "Mpesa";


$sql ="INSERT INTO `mpesa_transaction`(`Code`, `phonenumber`, `AccountNo`, `Amount_paid`, `PaymentMethod`)
                       VALUES ('$code','$phone_number','$ReceiptNo','$amount_paid','$payment_method')";

$result = DB::instance()->executeSQL($sql);
if($result){

    $sql ="SELECT * FROM `payments` WHERE `payments`.`Transaction_code`='$ReceiptNo'";
    $result = DB::instance()->executeSQL($sql);
    if($result){
        $paidValue =accountBalance($ReceiptNo);
        $expectedValue = $result->fetch_assoc()['Amount'];
        if($paidValue>=$expectedValue){
            //update the status of the tickets
            $sql = "UPDATE `payments` SET `Status` ='1',`payments`.`Mode`='$payment_method' WHERE `payments`.`Transaction_code`='$ReceiptNo'";
            $result = DB::instance()->executeSQL($sql);
            //send tickets
            $sql ="SELECT * FROM `payments` WHERE `payments`.`Transaction_code`='$ReceiptNo'";
            $result = DB::instance()->executeSQL($sql);
            while ($row =$result->fetch_assoc()){
                $Trans = $row['Transaction_code'];
                $extension=getmessagedata($ReceiptNo);
                $message= " Successfully paid $extension. Receipt number is $Trans";
                sendSMS($phone_number,$message);
            }

            $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message = "Tickets Sent";
            $response->success = true;
            echo json_encode($response);
            exit();


        }else{
            $balance = $expectedValue-$paidValue;
            sendSMS($phone_number,"Please complete your payment in order to completete the transaction Paid KES. $paidValue expected KES.$expectedValue . Balance KES.$balance");
            $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message = "Receipt  not Sent Pay full the amount";
            $response->success = false;
            echo json_encode($response);
            exit();
        }
    }
}


function confirmatioCode(){
    $ConfCode = new ConfirmationCode;
    $code = $ConfCode->auto(4);
    return $code;
}


function formatPhoneNumber($phoneNumber) {
    $phoneNumber = preg_replace('/[^\dxX]/', '', $phoneNumber);
    $phoneNumber = preg_replace('/^0/','254',$phoneNumber);

    $phoneNumber = $phone = preg_replace('/\D+/', '', $phoneNumber);

    return $phoneNumber;
}


function sendSMS($phoneNumber,$message){
    $username   = "Nouveta";
    $apikey     = "df338bb1b4ce3c568e0bbf619d1ffde365f820e1d9a89eb5d77ab7d298997e0d";

    $gateway    = new AfricasTalkingGateway($username, $apikey);
    try
    {
        // Thats it, hit send and we'll take care of the rest.
        $results = $gateway->sendMessage($phoneNumber, $message,'NOUVETA');

        foreach($results as $result) {
            /* // status is either "Success" or "error message"
             echo " Number: " .$result->number;
             echo " Status: " .$result->status;
             echo " MessageId: " .$result->messageId;
             echo " Cost: "   .$result->cost."\n";*/
        }
    }
    catch ( AfricasTalkingGatewayException $e )
    {
        //  echo "Encountered an error while sending: ".$e->getMessage();
    }

}

function accountBalance($ReceiptNo) {
    $sqlTotal = "SELECT SUM(amount_paid)
                       FROM `mpesa_transaction`
                       WHERE  `mpesa_transaction`.`AccountNo`= '$ReceiptNo'";

    $results = DB::instance()->executeSQL($sqlTotal);

    if ($results)
        return $results->fetch_array()[0];

}

function getmessagedata($ReceiptNo){
    $sql="SELECT `iD`, `BusinessNo`, `Type`, `Year`, `Transaction_code` FROM `businesspermit` WHERE `Transaction_code`='$ReceiptNo'";
    $result= DB::instance()->executeSQL($sql);
    $count=mysqli_num_rows($result);

    if($count>=1) {
        $type = 'Business';
        $No = $result->fetch_array()[1];
        $year = $result->fetch_array()[2];

        $data="for Bussiness permit business number $No for year $year";

        return $data;

    }else{
        $sql = "SELECT `ID`, `LandNo`, `Transaction_code` FROM `landrates` WHERE `Transaction_code`='$ReceiptNo'";
        $result = DB::instance()->executeSQL($sql);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {

            $No = $result->fetch_array()[1];
            $data="for landarate Land Number $No";

            return $data;

        }else{
            $sql="SELECT `ID`, `StructureNo`, `Type`, `MonthPaid`, `Transaction_code` FROM `monthrent` WHERE `Transaction_code`='$ReceiptNo'";
            $result = DB::instance()->executeSQL($sql);
            $count=mysqli_num_rows($result);
            if($count>=1) {
                $type = $result->fetch_array()[2];
                $No = $result->fetch_array()[1];

                $data="for $type structure No $No ";

                return $data;

            }else{
                $sql = "SELECT `iD`, `ParkingFee`, `Subscription`, `RegNo`, `parking_place`, `Transaction_code` FROM `parking` WHERE `Transaction_code`='$ReceiptNo'";
                $count = mysqli_num_rows($result);
                if ($count >= 1) {

                    $No = $result->fetch_array()[2];
                    $RegNo = $result->fetch_array()[3];
                    $data= "for $No fee Car No, $RegNo";

                    return $data;

                }else{
                }
            }
        }
    }
}

?>