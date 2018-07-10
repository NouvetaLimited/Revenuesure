<?php

 include 'includes/DB.php';
 include 'helpers/Response.php';
 include 'helpers/ConfirmationCode.php';
 include "helpers/AfricasTalkingGateway.php";
 //include 'helpers/fpdf/fpdf.php';

 date_default_timezone_set("Africa/Nairobi");
header("Access-Control-Allow-Origin: *");


if(!empty($_POST['function']))
    $function = $_POST['function'];

if(!empty($_GET['function']))
    $function = $_GET['function'];

if(!empty($_POST['function']))
    $function = $_POST['checkout'];

if(!empty($_REQUEST['function']))
    $function = $_REQUEST['function'];




function getReceipt(){
    $today = date("d");
    $rand = strtoupper(substr(uniqid(sha1(time())),0,4));

    return $unique = $today . $rand;
}

$Transaction_code = getReceipt();
$TransactionDesc="REVENUESURE";


if($function=="businesspermit"){

    $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%D %M %Y') as Date, `businesspermit`.`Type`, sum(`payments`.`Amount`) as total, `payments`.`Mode` FROM `businesspermit` LEFT JOIN `payments` ON `businesspermit`.`Transaction_code` =      `payments`.`Transaction_code` WHERE `payments`.`Status`=1 GROUP BY DATE_FORMAT(`payments`.`Date`,'%D %M %Y'),`businesspermit`.`Type`,`payments`.`Mode` ORDER BY DATE_FORMAT(`payments`.`Date`,'%D %M %Y') DESC";

    $result = DB::instance()->executeSQL($sql);

    $Row= array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);

    $myJSONBusinesspermit= json_encode($Row);
    echo  $myJSONBusinesspermit;

}
if($function=='businesspermitmonth'){

    $sql ="SELECT DATE_FORMAT(`payments`.`Date`,'%M %Y') as Date, `businesspermit`.`Type`, sum(`payments`.`Amount`) as total, `payments`.`Mode` FROM `businesspermit` LEFT JOIN `payments` ON `businesspermit`.`Transaction_code` = `payments`.`Transaction_code` WHERE `payments`.`Status`=1 GROUP BY DATE_FORMAT(`payments`.`Date`,'%M %Y'),`businesspermit`.`Type`,`payments`.`Mode` ORDER BY DATE_FORMAT(`payments`.`Date`,'%M %Y')";
    $result = DB::instance()->executeSQL($sql);

    $Row= array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);


    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();

}

if($function=='landrates') {


    $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%D%M %Y')as day, `landrates`.`LandNo`, `payments`.`Amount`, `payments`.`Mode`\n"

        . "FROM `landrates`\n"

        . "LEFT JOIN `payments` ON `landrates`.`Transaction_code` = `payments`.`Transaction_code`\n"

        . "WHERE `payments`.`Status` >= 1\n"

        . "ORDER BY `payments`.`Date` DESC";


    $result = DB::instance()->executeSQL($sql);

    $Row = array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();

}
 if($function=="landratesmonthly"){

     $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%M%Y')as day , sum(`payments`.`Amount`) as total\n"

         . "FROM `landrates`\n"

         . "LEFT JOIN `payments` ON `landrates`.`Transaction_code` = `payments`.`Transaction_code`\n"

         . "WHERE `payments`.`Status` >= 1\n"

         . "GROUP BY DATE_FORMAT(`payments`.`Date`,'%M%Y')\n"

         . "ORDER BY `payments`.`Date` DESC";


     $result = DB::instance()->executeSQL($sql);

     $Row = array();
     $Row = $result->fetch_all(MYSQLI_ASSOC);

     $response = new Response();
     $response->status = Response::STATUS_SUCCESS;
     $response->data=$Row;
     $response->success = true;
     echo json_encode($response);
     exit();




 }


if($function=='parking'){
    $sql = "SELECT `payments`.`Date`,'%D%M %Y'as Date, `parking`.`parking_place`, `payments`.`Amount` as Amount, `payments`.`mode` FROM `payments` LEFT JOIN `parking` ON `parking`.`Transaction_code` = `payments`.`Transaction_code` WHERE `payments`.`Status`>=1  ORDER BY `payments`.`Date`,'%D%M %Y' DESC";


    $result = DB::instance()->executeSQL($sql);

      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();



}

if($function=='parkingmonth'){
    $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%M %Y')as Date, `parking`.`parking_place`, sum(`payments`.`Amount`) as Amount, `payments`.`mode` FROM `payments` LEFT JOIN `parking` ON `parking`.`Transaction_code` =            `payments`.`Transaction_code` WHERE `payments`.`Status`>=1 GROUP BY DATE_FORMAT(`payments`.`Date`,'%M %Y'),`parking`.`parking_place`,`payments`.`mode` ORDER BY DATE_FORMAT(`payments`.`Date`,'%M %Y') DESC";

    $result = DB::instance()->executeSQL($sql);

    $Row= array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();

}
if($function=='rent'){

    $sql = "SELECT DATE_FORMAT(`payments`.`date`,'%D%M%Y') as day,`monthrent`.`Type`,sum(`payments`.`Amount`) as total,`payments`.`Mode`\n"

        . "FROM `monthrent`\n"

        . "LEFT JOIN `payments` on `monthrent`.`Transaction_code`=`payments`.`Transaction_code`\n"

        . "WHERE `payments`.`Status`>=1\n"

        . "GROUP BY DATE_FORMAT(`payments`.`date`,'%D%M%Y'),`monthrent`.`Type`,`payments`.`Mode`\n"

        . "ORDER BY `payments`.`Date` DESC";

    $result = DB::instance()->executeSQL($sql);

      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();
}
if($function=='rentmonthly'){

    $sql = "SELECT DATE_FORMAT(`payments`.`date`,'%M%Y') as day,`monthrent`.`Type`,sum(`payments`.`Amount`) as total,`payments`.`Mode`\n"

        . "FROM `monthrent`\n"

        . "LEFT JOIN `payments` on `monthrent`.`Transaction_code`=`payments`.`Transaction_code`\n"

        . "WHERE `payments`.`Status`>=1\n"

        . "GROUP BY DATE_FORMAT(`payments`.`date`,'%M%Y'),`monthrent`.`Type`"

        . "ORDER BY `payments`.`Date` DESC";

    $result = DB::instance()->executeSQL($sql);

    $Row= array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();
}


  if($function=='tourism'){
      $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%D%M%Y') as Day,`tourism`.`ParkName`,SUM(`payments`.`Amount`) as total,`payments`.`Mode`
                FROM `tourism`
                LEFT JOIN `payments` on `tourism`.`Transaction_code`=`payments`.`Transaction_code`
                WHERE `payments`.`Date`>=1
                GROUP BY DATE_FORMAT(`payments`.`Date`,'%D%M%Y'),`tourism`.`ParkName`,`payments`.`Mode`
                ORDER BY `payments`.`Date` DESC ";


      $result = DB::instance()->executeSQL($sql);
      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

      $response = new Response();
      $response->status = Response::STATUS_SUCCESS;
      $response->data=$Row;
      $response->success = true;
      echo json_encode($response);
      exit();

}
if($function=='tourismmonthly'){
    $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%M%Y') as Day,`tourism`.`ParkName`,SUM(`payments`.`Amount`) as total,`payments`.`Mode`
                FROM `tourism`
                LEFT JOIN `payments` on `tourism`.`Transaction_code`=`payments`.`Transaction_code`
                WHERE `payments`.`Date`>=1
                GROUP BY DATE_FORMAT(`payments`.`Date`,'%M%Y'),`tourism`.`ParkName`,`payments`.`Mode`
                ORDER BY `payments`.`Date` DESC ";


    $result = DB::instance()->executeSQL($sql);
    $Row= array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();

}

if($function=='utility'){
    $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%D%M%Y') as Day, `utilites`.`Type_of_utility`, SUM(`payments`.`Amount`) as total, `payments`.`Mode` FROM `utilites` LEFT JOIN `payments` ON `utilites`.`Transaction_code`=`payments`.`Transaction_code` WHERE `payments`.`Status` >=1 GROUP BY DATE_FORMAT(`payments`.`Date`,'%D%M%Y'),`utilites`.`Type_of_utility`,`payments`.`Mode`ORDER BY DATE_FORMAT(`payments`.`Date`,'%D%M%Y') DESC";


    $result = DB::instance()->executeSQL($sql);
      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();

}
if($function=='MiscelleneousMonthly'){

    $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%M%Y') as Day, `utilites`.`Type_of_utility`, SUM(`payments`.`Amount`) as total,payments.Mode  FROM `utilites` LEFT JOIN `payments` ON `utilites`.`Transaction_code`=`payments`.`Transaction_code` WHERE `payments`.`Status` >=1 GROUP BY DATE_FORMAT(`payments`.`Date`,'%M%Y'),`utilites`.`Type_of_utility`,payments.Mode ORDER BY DATE_FORMAT(`payments`.`Date`,'%M%Y') DESC";


    $result = DB::instance()->executeSQL($sql);
    $Row= array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);

    $response = new Response();
    $response->status = Response::STATUS_SUCCESS;
    $response->data=$Row;
    $response->success = true;
    echo json_encode($response);
    exit();
}

  if($function=='Others'){


      $sql = "DATE_FORMAT(`payments`.`Date`,'%W %M %Y')as Day,day(`payments`.`Date`),monthname(`payments`.`Date`, `others`.`Type`, sum(`payments`.`Amount`) as amount, `transaction_type`.`TransactionType`\n"

          . "FROM `others`\n"

          . "    LEFT JOIN `payments` ON `others`.`Transaction_code` = `payments`.`Transaction_code`\n"

          . "    LEFT JOIN `transaction_type` ON `payments`.`TransactionID` = `transaction_type`.`TransactionID`\n"

          . "WHERE `others`.`Type` IS NOT NULL AND `payments`.`Date`>=current_date\n"

          . "GROUP BY day(`payments`.`Date`),monthname(`payments`.`Date`),`transaction_type`.`TransactionType`";

      $result = DB::instance()->executeSQL($sql);
      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

      $myJSONothers= json_encode($Row);

      echo $myJSONothers;

  }

  if($function=='Othersmonthly'){


      $sql = "SELECT monthname(`payments`.`Date`) as month, `others`.`Type`, sum(`payments`.`Amount`) as amount, `transaction_type`.`TransactionType`\n"

          . "FROM `others`\n"

          . "    LEFT JOIN `payments` ON `others`.`Transaction_code` = `payments`.`Transaction_code`\n"

          . "    LEFT JOIN `transaction_type` ON `payments`.`TransactionID` = `transaction_type`.`TransactionID`\n"

          . "WHERE `others`.`Type` IS NOT NULL\n"

          . "GROUP BY monthname(`payments`.`Date`),`others`.`Type`";

      $result = DB::instance()->executeSQL($sql);
      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

      $myJSONothersmonthly= json_encode($Row);

      echo $myJSONothersmonthly;

  }
if ($function=='Transactions'){
    $sql= "SELECT cast(`payments`.`Date` as DATE), `revenue_sources`.`RevSource`, `payments`.`Amount`\n"

        . "FROM `revenue_sources`\n"

        . "    LEFT JOIN `payments` ON `payments`.`RevID` = `revenue_sources`.`RevID`";
    $result = DB::instance()->executeSQL($sql);
    $Row= array();
    $Row = $result->fetch_all(MYSQLI_ASSOC);

    $myJSONtransactions= json_encode($Row);

    echo $myJSONtransactions;
}

if($function=='daily'){

      $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%D %b %Y') as Date, `revenue_sources`.`RevSource`, sum(`payments`.`Amount`)as Total\n"

          . "FROM `revenue_sources`\n"

          . "    LEFT JOIN `payments` ON `payments`.`RevID` = `revenue_sources`.`RevID`\n"

          . "  GROUP BY DATE_FORMAT(`payments`.`Date`,'%W %M %Y'),`revenue_sources`.`RevSource`\n"

          . "  ORDER BY `payments`.`Date` DESC";

      $result = DB::instance()->executeSQL($sql);
      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

      $myJSONdaily= json_encode($Row);

      echo $myJSONdaily;

  }

  if($function=='weekly'){
      $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%V %Y'), sum(`payments`.`Amount`) as total, `revenue_sources`.`RevSource`\n"

          . "FROM `revenue_sources`\n"

          . "    LEFT JOIN `payments` ON `payments`.`RevID` = `revenue_sources`.`RevID`\n"

          . "GROUP BY DATE_FORMAT(`payments`.`Date`,'%V %Y'),`revenue_sources`.`RevSource`";

      $result = DB::instance()->executeSQL($sql);
      $Row= array();


      $Voke= json_encode($Row);

      echo $Voke;
  }

  if($function=='monthly'){


      $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%M %Y') as Month, sum(`payments`.`Amount`) as total, `revenue_sources`.`RevSource`\n"

          . "FROM `revenue_sources`\n"

          . "    LEFT JOIN `payments` ON `payments`.`RevID` = `revenue_sources`.`RevID`\n"

          . "GROUP BY DATE_FORMAT(`payments`.`Date`,'%M %Y'),`revenue_sources`.`RevSource`"
          . "ORDER BY `payments`.`Date` DESC";

      $result = DB::instance()->executeSQL($sql);
      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

      $myJSONmonthly= json_encode($Row);

      echo $myJSONmonthly;

  }
  if($function=='yearly'){
      $sql = "SELECT DATE_FORMAT(`payments`.`Date`,'%Y') as year, sum(`payments`.`Amount`) as total, `revenue_sources`.`RevSource`\n"

          . "FROM `revenue_sources`\n"

          . "    LEFT JOIN `payments` ON `payments`.`RevID` = `revenue_sources`.`RevID`\n"

          . "GROUP BY DATE_FORMAT(`payments`.`Date`,'%Y') ,`revenue_sources`.`RevSource`";

      $result = DB::instance()->executeSQL($sql);
      $Row= array();
      $Row = $result->fetch_all(MYSQLI_ASSOC);

      $myJSONyear= json_encode($Row);

      echo $myJSONyear;

  }

if($function=='BpRenew'){

    $Amount = BpRenewfee();
    $RevID = '2';
    $BusinessNo = $_REQUEST['BusinessNo'];
    $Type = 'Renew';
    $phonenumber=formatPhoneNumber($_REQUEST['PhoneNo']);
    $Year=$_REQUEST['Year'];
    $payBill='175555';

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) VALUES ('$Transaction_code','$Amount','$RevID')";
    $result2 = DB::instance()->executeSQL($sql);


    $sql="INSERT INTO `businesspermit`( `BusinessNo`, `Type`,`Year`,`Transaction_code`) VALUES ('$BusinessNo','$Type','$Year','$Transaction_code')";
    $result=DB::instance()->executeSQL($sql);

    if($result && $result2){
        echo"success";
        $data ="To renew your businesspermit for BusinessNumber:$BusinessNo, please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

            pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);

            sendSMS($phonenumber,$data);
            $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message= "Notification sent to your phone to make payment";
            $response->data =$Transaction_code;
            $response->success = true;
            echo json_encode($response);
            exit();
    }else{

        echo "failed";
        $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message= "failed";
            $response->success = false;
            echo json_encode($response);
            exit();

    }
}
if($function=='Bpnew'){

    $Amount = Bpnewfee();
    $RevID = '2';
    $BusinessNo = $_REQUEST['BusinessNo'];
    $Type = 'new';
    $phonenumber=formatPhoneNumber($_REQUEST['PhoneNo']);
    $Year=$_REQUEST['Year'];
    $payBill='175555';

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) VALUES ('$Transaction_code','$Amount','$RevID')";
    $result2 = DB::instance()->executeSQL($sql);


    $sql="INSERT INTO `businesspermit`( `BusinessNo`, `Type`,`Year`,`Transaction_code`) VALUES ('$BusinessNo','$Type','$Year','$Transaction_code')";
    $result=DB::instance()->executeSQL($sql);

    if($result && $result2){
        echo"success";
        $data ="To get your businesspermit for BusinessNumber:$BusinessNo, please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

            pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);

            sendSMS($phonenumber,$data);
            $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message= "Notification sent to your phone to make payment";
            $response->data =$Transaction_code;
            $response->success = true;
            echo json_encode($response);
            exit();
    }else{

        echo "failed";
        $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message= "failed";
            $response->success = false;
            echo json_encode($response);
            exit();

    }
}
  if($function=="CreateLandRate") {
      $LandNo = $_REQUEST['LandNo'];
      $Amount = landrateFee();
      $RevID = '4';
      $phonenumber=formatPhoneNumber($_REQUEST['PhoneNo']);
      $payBill='175555';


      $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) VALUES ('$Transaction_code','$Amount','$RevID')";
      $result=DB::instance()->executeSQL($sql);
      $sql = "INSERT INTO `landrates`(`LandNo`, `Transaction_code`) VALUES ('$LandNo','$Transaction_code')";
      $result2=DB::instance()->executeSQL($sql);
      if($result && $result2){

           $data ="To pay your Landrate for LandNo:$LandNo, please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

            pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);

            sendSMS($phonenumber,$data);
            $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message= "Notification sent to your phone to make payment";
            $response->data =$Transaction_code;
            $response->success = true;
            echo json_encode($response);
            exit();
                }else{
          echo "failed";
         $response = new Response();
            $response->status = Response::STATUS_SUCCESS;
            $response->message= "failed";
            $response->success = false;
            echo json_encode($response);
            exit();
      }


  }
   if($function=='ParkingDaily') {
       $Amount =ParkingDailyFee();
       $RevID = '1';
       $Subscription = 'Daily';
       $RegNo = $_REQUEST['RegNo'];
       $parking_place = $_REQUEST['parking_place'];
       $payBill='175555';
       $phonenumber=formatPhoneNumber($_REQUEST['phonenumber']);
       $ParkingFee=ParkingDailyFee();

       $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`,`RevID`) 
              VALUES ('$Transaction_code','$Amount','$RevID')";
       $result2 = DB::instance()->executeSQL($sql);
       $sql = "INSERT INTO `parking`(`ParkingFee`, `Subscription`, `RegNo`, `parking_place`, `Transaction_code`) 
                                VALUES ('$ParkingFee','$Subscription','$RegNo','$parking_place','$Transaction_code')";
       $result = DB::instance()->executeSQL($sql);


       if($result && $result2){
           $data ="To pay your $Subscription Parking fee for Car Reg:$RegNo at $parking_place, please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation      message. if paid and received the confirmation, ignore this SMS.";

          pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);
           sendSMS($phonenumber,$data);
           $response = new Response();
           $response->status = Response::STATUS_SUCCESS;
           $response->message= "Notification sent to your phone to make payment";
           $response->data =$Transaction_code;
           $response->success = true;
           echo json_encode($response);
           exit();
       }else{

           $response = new Response();
           $response->status = Response::STATUS_SUCCESS;
           $response->message= "failed";
           $response->success = false;
           echo json_encode($response);
           exit();
       }

   }
   If($function=='ParkingMonthly'){
       $Amount = ParkingMonthlyFee();
       $RevID = '1';
       $Subscription = 'Monthly';
       $RegNo = $_REQUEST['RegNo'];
       $parking_place = $_REQUEST['parking_place'];
       $payBill='175555';
       $phonenumber=formatPhoneNumber($_REQUEST['phonenumber']);
       $ParkingFee='1500';

       $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) 
              VALUES ('$Transaction_code','$Amount','$RevID')";
       $result2 = DB::instance()->executeSQL($sql);
       $sql = "INSERT INTO `parking`(`ParkingFee`, `Subscription`, `RegNo`, `parking_place`, `Transaction_code`) 
                                VALUES ('$ParkingFee','$Subscription','$RegNo','$parking_place','$Transaction_code')";
       $result = DB::instance()->executeSQL($sql);


       if($result && $result2){
           $data ="To pay your $Subscription Parking fee for Car Reg:$RegNo at $parking_place, please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

           pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);

           sendSMS($phonenumber,$data);
           $response = new Response();
           $response->status = Response::STATUS_SUCCESS;
           $response->message= "Notification sent to your phone to make payment";
           $response->data =$Transaction_code;
           $response->success = true;
           echo json_encode($response);
           exit();
       }else{

           $response = new Response();
           $response->status = Response::STATUS_SUCCESS;
           $response->message= "failed";
           $response->success = false;
           echo json_encode($response);
           exit();
       }


   }
if($function=='Parkingpenalties') {
    $Amount = 300;
    $RevID = '1';
    $Subscription = 'Penalty';
    $RegNo = $_REQUEST['RegNo'];
    $parking_place = $_REQUEST['parking_place'];
    $payBill = '175555';
    $phonenumber = formatPhoneNumber($_REQUEST['phonenumber']);
    $ParkingFee = '300';

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) 
              VALUES ('$Transaction_code','$Amount','$RevID')";
    $result2 = DB::instance()->executeSQL($sql);
    $sql = "INSERT INTO `parking`(`ParkingFee`, `Subscription`, `RegNo`, `parking_place`, `Transaction_code`) 
                                VALUES ('$ParkingFee','$Subscription','$RegNo','$parking_place','$Transaction_code')";
    $result = DB::instance()->executeSQL($sql);


    if ($result && $result2) {
        $data = "To pay your $Subscription  for Car Reg:$RegNo , please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

        pushPayments($Amount, $phonenumber, $Transaction_code,$TransactionDesc);

        sendSMS($phonenumber, $data);
        $response = new Response();
        $response->status = Response::STATUS_SUCCESS;
        $response->message = "Notification sent to your phone to make payment";
        $response->data = $Transaction_code;
        $response->success = true;
        echo json_encode($response);
        exit();
    } else {

        $response = new Response();
        $response->status = Response::STATUS_SUCCESS;
        $response->message = "failed";
        $response->success = false;
        echo json_encode($response);
        exit();
    }
}
if($function=='Createothers'){
    $Amount = $_REQUEST['Amount'];
    $RevID = $_REQUEST['RevID'];
    $TransactionID = $_REQUEST['TransactionID'];
    $Type = $_REQUEST['Type'];

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`, `TransactionID`) VALUES ('$Transaction_code','$Amount','$RevID','$TransactionID')";
    $result2 = DB::instance()->executeSQL($sql);;

    $sql="INSERT INTO `others`( `Type`, `Transaction_code`) VALUES ('$Type','$Transaction_code')";
    $result=DB::instance()->executeSQL($sql);
    if($result && $result2){
        echo"success";
    }else{

        echo "failed";
    }
}

 if($function=='CreateRevenuesources'){

     $RevSource=$_REQUEST['RevSource'];

    $sql="INSERT INTO `revenue_sources`(`RevSource`) VALUES ('$RevSource')";
     $result= DB::instance()->executeSQL($sql);
     if($result){
         echo 'success';
     }else{
         'failed';
     }
 }
if($function=='createtourism'){
    $Amount = $_REQUEST['Amount'];
    $RevID = $_REQUEST['RevID'];
    $TransactionID = $_REQUEST['TransactionID'];
    $ParkName = $_REQUEST['ParkName'];

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`, `TransactionID`) VALUES ('$Transaction_code','$Amount','$RevID','$TransactionID')";
    $result2 = DB::instance()->executeSQL($sql);
    $sql="INSERT INTO `tourism`( `ParkName`, `Transaction_code`) VALUES ('$ParkName','$Transaction_code')";
    $result= DB::instance()->executeSQL($sql);

    if($result && $result2){
        echo 'success';
    }else{

        echo "fail";
    }
}
if($function=='WaterBill'){
    $Amount = '300';
    $RevID = '7';
    $Type_of_utility = 'WaterBill';
    $phonenumber=formatPhoneNumber($_REQUEST['PhoneNo']);
    $payBill='175555';

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) VALUES ('$Transaction_code','$Amount','$RevID')";
    $result2 = DB::instance()->executeSQL($sql);
    $sql="INSERT INTO `UTILITES`(`Transaction_code`, `Type_of_utility`) VALUES ('$Transaction_code','$Type_of_utility')";
    $result= DB::instance()->executeSQL($sql);

    if($result && $result2){
        $data ="To pay your Water bill  please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

        pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);

        sendSMS($phonenumber,$data);
        $response = new Response();
        $response->status = Response::STATUS_SUCCESS;
        $response->message= "Notification sent to your phone to make payment";
        $response->data =$Transaction_code;
        $response->success = true;
        echo json_encode($response);
        exit();
    }else{

        echo "fail";
    }
}
if ($function=='Payinghouserent'){

    $Amount =Houserent();
    $RevID = '3';
    $StructureNo=$_REQUEST['StructureNo'];
    $Type='HouseRent';
    $phonenumber=formatPhoneNumber($_REQUEST['phonenumber']);
    $payBill='175555';

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) VALUES ('$Transaction_code','$Amount','$RevID')";
    $result2 = DB::instance()->executeSQL($sql);

    $sql="INSERT INTO `monthrent`(`StructureNo`, `Type`, `Transaction_code`)VALUES ('$StructureNo','$Type','$Transaction_code')";
    $result= DB::instance()->executeSQL($sql);

    if($result2&&$result){
        $data ="To pay your Houserent for House:$StructureNo please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

        pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);

        sendSMS($phonenumber,$data);
        $response = new Response();
        $response->status = Response::STATUS_SUCCESS;
        $response->message= "Notification sent to your phone to make payment";
        $response->data =$Transaction_code;
        $response->success = true;
        echo json_encode($response);
        exit();
    }else{

        $response = new Response();
        $response->status = Response::STATUS_SUCCESS;
        $response->message= "failed";
        $response->success = false;
        echo json_encode($response);
        exit();
    }
}
if($function=='PayingmarketRent'){
    $Amount =Stallrent();
    $RevID = 3;
    $StructureNo=$_REQUEST['StructureNo'];
    $Type='StallRent';
    $phonenumber=formatPhoneNumber($_REQUEST['phonenumber']);
    $payBill='256666';

    $sql = "INSERT INTO `payments`(`Transaction_code`, `Amount`, `RevID`) VALUES ('$Transaction_code','$Amount','$RevID')";
    $result2 = DB::instance()->executeSQL($sql);

    $sql="INSERT INTO `monthrent`(`StructureNo`, `Type`, `Transaction_code`)VALUES ('$StructureNo','$Type','$Transaction_code')";
    $result= DB::instance()->executeSQL($sql);

    if($result2){
        $data ="To pay your StallRent for Stall:$StructureNo please send KES $Amount to paybill number $payBill Account number $Transaction_code. Once payment is confirmed.You'll get a confirmation message. if paid and received the confirmation, ignore this SMS.";

        pushPayments($Amount,$phonenumber,$Transaction_code,$TransactionDesc);

        sendSMS($phonenumber,$data);
        $response = new Response();
        $response->status = Response::STATUS_SUCCESS;
        $response->message= "Notification sent to your phone to make payment";
        $response->data =$Transaction_code;
        $response->success = true;
        echo json_encode($response);
        exit();
    }else{

        $response = new Response();
        $response->status = Response::STATUS_SUCCESS;
        $response->message= "failed";
        $response->success = false;
        echo json_encode($response);
        exit();
    }
}


function formatPhoneNumber($phoneNumber) {
    $phoneNumber = preg_replace('/[^\dxX]/', '', $phoneNumber);
    $phoneNumber = preg_replace('/^0/','254',$phoneNumber);

    $phoneNumber = $phone = preg_replace('/\D+/', '', $phoneNumber);

    return $phoneNumber;
}
function pushPayments($Amount, $phoneNumber, $AccountReference,$TransactionDesc){


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://payme.ticketsoko.com/api/index.php",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"function\"\r\n\r\nCustomerPayBillOnline\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PayBillNumber\"\r\n\r\n175555\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"Amount\"\r\n\r\n$Amount\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PhoneNumber\"\r\n\r\n$phoneNumber\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"AccountReference\"\r\n\r\n$AccountReference\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"TransactionDesc\"\r\n\r\n$TransactionDesc\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
        CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache",

            "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
            "postman-token: 4fe6b48a-5c0a-e9fa-7d45-172ce8b64722"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

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
function ParkingDailyFee(){
    $sql = "SELECT  `PaymentsType`, `Amount` FROM `rates` \n"

        . "WHERE `PaymentsType` like 'ParkingDaily'";


    $parkingdailyfee = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $parkingdailyfee;

}
function ParkingMonthlyFee(){
    $sql = "SELECT  `PaymentsType`, `Amount` FROM `rates` \n"

        . "WHERE `PaymentsType` like 'ParkingMonthly'";


    $parkingmonthlyfee = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $parkingmonthlyfee;

}
function ParkingyearlyFee(){
    $sql = "SELECT  `PaymentsType`, `Amount` FROM `rates` \n"

        . "WHERE `PaymentsType` like 'ParkingYearly'";


    $parkingyearlyfee = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $parkingyearlyfee;

}
function landrateFee(){
    $sql = "SELECT  `PaymentsType`, `Amount` FROM `rates` \n"

        . "WHERE `PaymentsType` like 'Land'";


    $landrate = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $landrate;

}
function BpRenewfee(){
    $sql = "SELECT  `PaymentsType`,`Amount` FROM `rates` \n"

        . "WHERE `PaymentsType` like 'Renew'";


    $renew = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $renew;

}
function Bpnewfee(){
    $sql = "SELECT  `PaymentsType`, `Amount` FROM `rates` "

        . "WHERE `PaymentsType` like 'New'";


    $new = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $new;

}
function Houserent(){
    $sql = "SELECT  `PaymentsType`, `Amount` FROM `rates` "

        . "WHERE `PaymentsType` like 'HouseRent'";


    $houserent = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $houserent;

}
function Stallrent(){
    $sql = "SELECT  `PaymentsType`, `Amount` FROM `rates` "

        . "WHERE `PaymentsType` like 'StallRent'";


    $stallrent = DB::instance()->executeSQL($sql)->fetch_array()[1];

    return $stallrent;

}

if($function=='Changerates') {
    $newrate = $_REQUEST['Newrate'];
    $Paymenttype = $_REQUEST['Revenuesource'];

    $sql = "UPDATE `rates` SET `Amount`='$newrate' where `PaymentsType` like '$Paymenttype'";

    $result = $result = DB::instance()->executeSQL($sql);
    if ($result) {
        echo "success";
        echo $Paymenttype;
        echo "amount";
        echo  $newrate;
    } else {
        echo "failed";
    }
}
if($function=="loginDashboard"){

    session_start();
    if(isset($_SESSION["email"])){
        session_destroy();
    }
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];


    $email = stripslashes($email);
    $email = addslashes($email);
    $password = stripslashes($password);
    $password = addslashes($password);
    $sql="SELECT * FROM registration WHERE (`Email`= '$email' or `IDNO_PASSNO`='$email' or `PhoneNumber`='$email' and `Password`= '$password') or die('Error')";
     $result=DB::instance()->executeSQL($sql);
    $count=mysqli_num_rows($result);
    if($count==1){
        while($row = mysqli_fetch_array($result)) {
            $name = $row['name'];
        }
        setcookie($row['`regid`']);
        $_SESSION["name"] = $name;
        $_SESSION["email"] = $email;
        header("location:permit.html");
    }
    else
        header("location:$ref?w=Wrong Username or Password");

}
if($function=="register") {

    $IDNO = $_REQUEST['Idno'];
    $FirstName = $_REQUEST['username'];
    $Phonenumber = $_REQUEST['phonenumber'];
    $email = $_REQUEST['Email'];
    $password = $_REQUEST['password'];
    $prepassword = $_REQUEST['prepassword'];


    if ($password == $prepassword) {
        $sql = "SELECT * FROM registration WHERE (`Email`= '$email' or `IDNO`='$email') and `Password`= '$password'";
        $result = DB::instance()->executeSQL($sql);
        $count = mysqli_num_rows($result);
        if ($count >= 1) {
            $message = "IDNO OR EMAILADRESS has been registered\\nTry again.";
            echo "<script type='text/javascript'>alert('$message');document.location='http://localhost/rcounty/dashboard/login.html'</script>";
        } else {
            $sql = "INSERT INTO `registration`(`IDNo`, `FirstName`, `PhoneNumber`, `Email`, `Password`)
     VALUES ('$IDNO','$FirstName','$Phonenumber','$email','$password')";

            $result = DB::instance()->executeSQL($sql);
            if ($result) {
                $message = "REGISTERED SUCCESSFULL.\\nLOG IN.";
                echo "<script type='text/javascript'>alert('$message');document.location='http://localhost/rcounty/dashboard/login.html'</script>";
            } else {
                echo $message = "Email already used.\\nTry again.";
                echo "<script type='text/javascript'>alert('$message');document.location='http://localhost/rcounty/dashboard/login.html'</script>";;
            }
        }


    } else {
        $message = "THE  Passwords did not match.\\nTry again.";
        echo "<script type='text/javascript'>alert('$message');document.location='http://localhost/rcounty/dashboard/login.html'</script>";
    }
}
if ($function=='transaction'){class PDF extends PDF_MySQL_Table
{
    function Header()
    {
        // Title
        $this->SetFont('Arial','',18);
        $this->Cell(0,6,'Transaction History',0,1,'C');
        $this->Ln(10);
        // Ensure table header is printed
        parent::Header();
    }
}

// Connect to database
    $link = mysqli_connect('localhost','root','','revenuesure');

    $pdf = new PDF();
    $pdf->AddPage();
// First table: output all columns
    $pdf->Table($link,'SELECT `payments`.`Transaction_code`,(`payments`.`Date`) as Day_Time,`payments`.`Amount`,(`revenue_sources`.`RevSource`) as Paid_for
FROM `payments`
LEFT JOIN `revenue_sources` on `revenue_sources`.`RevID`=`payments`.`RevID`');
    $pdf->AddPage();
// Second table: specify 3 columns
    $pdf->AddCol('rank',20,'','C');
    $pdf->AddCol('name',40,'Country');
    $pdf->AddCol('pop',40,'Pop (2001)','R');
    $prop = array('HeaderColor'=>array(255,150,100),
        'color1'=>array(210,245,255),
        'color2'=>array(255,255,210),
        'padding'=>2);
//$pdf->Table($link,'select RevSource, format(pop,0) as pop, rank from revenue_sources order by rank limit 0,10',$prop);
    $pdf->Output();
}
if($function=='addrevenueStream'){



}
