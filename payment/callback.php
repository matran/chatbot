<?php

      
        $callbackJSONData=file_get_contents('php://input');
        $callbackData=json_decode($callbackJSONData);
        $resultCode=$callbackData->Body->stkCallback->ResultCode;
        $resultDesc=$callbackData->Body->stkCallback->ResultDesc;
        $merchantRequestID=$callbackData->Body->stkCallback->MerchantRequestID;
        $checkoutRequestID=$callbackData->Body->stkCallback->CheckoutRequestID;
        $amount=$callbackData->Body->stkCallback->CallbackMetadata->Item[0]->Value;
        $mpesaReceiptNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
        $balance=$callbackData->stkCallback->Body->CallbackMetadata->Item[2]->Value;
        $transactionDate=$callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
        $phoneNumber=$callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;
        if($mpesaReceiptNumber!=null && $phoneNumber!=null){
     
             


            } else{





            } 




      public function sendEmail($email){


      }      
        
    }

?>