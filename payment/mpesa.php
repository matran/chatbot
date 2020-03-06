<?php
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mpesa= new \Safaricom\Mpesa\Mpesa();
  $email=$_REQUEST['email'];

   $BusinessShortCode='174379';
  $TransactionType= 'CustomerPayBillOnline';
  $Amount=$_REQUEST['amount'];
  $PartyA=$_REQUEST['phoneno'];
  $PartyB='174379';
  $PhoneNumber=$_REQUEST['phoneno'];
  $CallBackURL='http://127.0.0.1/chatbot/payment/callback.php?email='.$email.'';
  $AccountReference='Neucaton technologies';
  $TransactionDesc='Neucaton technologies';
  $Remarks='good';
  $LipaNaMpesaPasskey='bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
$stkPushSimulation=$mpesa->STKPushSimulation($BusinessShortCode, $LipaNaMpesaPasskey, $TransactionType, $Amount, $PartyA, $PartyB, $PhoneNumber, $CallBackURL, $AccountReference, $TransactionDesc, $Remarks);
 //echo $stkPushSimulation;
 $data=json_decode($stkPushSimulation);
 $resultCode=$data->ResponseCode;

 if($resultCode=='0'){

 	echo "<p>Request sent successfully.please enter mpesa pin in your phone to finish transaction</p>";
 }
   
$asset=$_REQUEST['asset'];

$message='You have bought '.$asset.' at a price of '.$Amount.'. Thank you for buying.';
sendMail($_REQUEST['email'],$message);


function sendMail($email,$message){
  $mail = new PHPMailer(true); 
  $useremail=$email;  
  $message=$message;                           // Passing `true` enables exceptions
try {
    //Server settings
   // $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'matranic@gmail.com';                 // SMTP username
    $mail->Password = 'Divine*henry123';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('matranic@gmail.com', 'Chatbot sales agent');
    $mail->addAddress($useremail, 'Joe User');     // Add a recipient
   // $mail->addAddress('matranic@gmail.com');               // Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Shopping details';
    $mail->Body    = $message;
   // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Email has been sent to your email address';
} catch (Exception $e) {
    echo 'Email has not been sent', $mail->ErrorInfo;
}

}


?>

