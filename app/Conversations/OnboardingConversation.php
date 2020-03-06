<?php

namespace App\Conversations;
use Validator;
use Illuminate\Foundation\Inspiring;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

class OnboardingConversation extends Conversation {


   // $stockprice=10000;
   // $whattobuy;
     
   

	public function askName(){
        $this->ask('Hello, Am a Chatbot, What is your name?', function(Answer $answer) {
            $this->say('Nice to meet you '. $answer->getText()); 
             $this->bot->userStorage()->save([
                     'person' => $answer->getText(),
                        ]);

             $this->askEmail();
        });
    }


    public function askEmail(){
    	 $this->ask('What is your email?,This one will be used when sending you email', function(Answer $answer) {
            $validator = Validator::make(['email' => $answer->getText()], [
                'email' => 'email',
            ]);
            if ($validator->fails()) {
                return $this->repeat('That doesn\'t look like a valid email. Please enter a valid email.');
            }

            $this->bot->userStorage()->save([
                'email' => $answer->getText(),
            ]);

            $this->askPhone();
        });
    }


    public function askPhone(){
         $this->ask('Great. What is your phone number?', function(Answer $answer) {
                 $phone=$answer->getText();
              if(preg_match_all('!\d+!', $phone, $match) ){
               $value=$match[0][0]; 
            if(strlen($value) <10 ||strlen($value)>10){

           return $this->repeat('That doesn\'t look like a valid phone. Please enter a valid phone number.');
            
           }else{

               $this->bot->userStorage()->save([
                'mobile' => $answer->getText(),
            ]);

            $this->say('Great!');
            $this->askWhatToBuy();

           }
      }else{
         
         return $this->repeat('That doesn\'t look like a valid phone. Please enter a valid phone number.');

      }

           
        });



    }


   

    public function askWhatToBuy(){

     $this->ask('What do you want to buy today?', function(Answer $answer) {
            $whattobuy=$answer->getText();
            $str=preg_replace('/\\s/',' ',$whattobuy);

           if (preg_match_all('#\b(cube 3d printer|Huawei y3|Nokia 110)\b#i', $str, $out)){
                $value=$out[0][0];
                if(strcasecmp($value, "cube 3d printer")==0){
                    
                     $this->bot->userStorage()->save([
                     'goods' => "50000",
                        ]);

                    $this->say('We have it,let me send you a photo and its price'); 
                    $attachment = new Image('http://127.0.0.1/chatbot/images/cube3d.jpg');
                    $message = OutgoingMessage::create('Cube 3d printer price :ksh.50,000.00')->withAttachment($attachment);
                    $this->say($message);
                    $this->askIfItIsOkeyToContinueBuying($value);



                }elseif (strcasecmp($value, "huawei y3")==0) {

                	$this->bot->userStorage()->save([
                     'goods' => "7000",
                        ]);

                    $this->say('We have it,let me send you a photo and its price'); 
                    $attachment = new Image('http://127.0.0.1/chatbot/images/huawei.jpg');
                    $message = OutgoingMessage::create('Huawei Y3 price :ksh.7,000.00')->withAttachment($attachment);
                    $this->say($message);
                    $this->askIfItIsOkeyToContinueBuying($value);


                	# code...
                }elseif (strcasecmp($value, "Nokia 110")==0) {
                	$this->bot->userStorage()->save([
                     'goods' => "3000",
                        ]);
                    $this->say('We have it,let me send you a photo and its price'); 
                    $attachment = new Image('http://127.0.0.1/pricenegotiator/cube3d.jpg');
                    $message = OutgoingMessage::create('Nokia 110 :ksh.3,000.00')->withAttachment($attachment);
                    $this->say($message);
                    $this->askIfItIsOkeyToContinueBuying($value);


                	# code...
                }else{

                return $this->repeat('Sorry,We do not have what you are saying right now');

                }


           }else{

              return $this->repeat('Sorry,We do not have what you are saying right now');
          


           }
            

              
          

       
        });


    }



 public function askIfItIsOkeyToContinueBuying($vale){

        $text='';
        $value=$vale;
        if(strcasecmp($value, "huawei y3")==0){
             $text='Phone';
        }
        

 	 $this->ask('It is a great ' .$text. ' .Is that what you are looking for?', function(Answer $answer) {
            $valAnswer=$answer->getText();
            $str=preg_replace('/\\s/',' ',$valAnswer);
          if (preg_match_all('#\b(yes|it is the one|that is)\b#i', $str, $out) ){ 

                  $this->startNegotiatingStep1();  

          }elseif (preg_match_all('#\b(no|it is not that one|nop)\b#i', $str, $out)) {

          	$this->say('Ok,thanks'); 
       
          }else{

               return $this->repeat('am not understanding you please repeat');
          }
         


      
        });


 }




public function startNegotiatingStep1(){
    

 	 $this->ask('How much will you give me?', function(Answer $answer) {
            $pricetext=$answer->getText();
             $minimum;
             $gds='';
             $firstprice;
             $data = $this->bot->userStorage()->find();
              $tobuy= $data->get('goods');

              if($tobuy=='7000'){
              	$minimum='5000';
              	$firstprice='6900';
              	$gds='Huawei y3 phone';
              	 $this->bot->userStorage()->save([
                     'phone' => $gds,
                        ]);

              	 $this->bot->userStorage()->save([
                     'firstprice' => $firstprice,
                        ]);

              	 $this->bot->userStorage()->save([
                     'minimum' => $minimum,
                        ]);
              }elseif($tobuy=='3000'){


                 	$minimum='2000';
              	$firstprice='2900';
              	$gds='Nokia 110 phone';
              	 $this->bot->userStorage()->save([
                     'phone' => $gds,
                        ]);

              	 $this->bot->userStorage()->save([
                     'firstprice' => $firstprice,
                        ]);

              	 $this->bot->userStorage()->save([
                     'minimum' => $minimum,
                        ]);

              }elseif($tobuy=='50000'){


                  	$minimum='40000';
              	$firstprice='49000';
              	$gds='Cube 3d printer';
              	 $this->bot->userStorage()->save([
                     'phone' => $gds,
                        ]);

              	 $this->bot->userStorage()->save([
                     'firstprice' => $firstprice,
                        ]);

              	 $this->bot->userStorage()->save([
                     'minimum' => $minimum,
                        ]);


              }
     
            if(preg_match_all('!\d+!', $pricetext, $match) ){
                $val=$match[0][0];
                  if($val==$tobuy || $val>$tobuy){
                  $this->say('Thats Good price,let me send you a link for payment'); 
                  	$this->agreeState($val);	
                }elseif($val <$tobuy){
                   $this->ask('That is totally unrealistic.The stocker price is '.$tobuy. ' and this '.$gds. '  has lots of extras,so at value '.$tobuy.', it is a very good value',function(Answer $answer){

                                 $priceText2=$answer->getText();

                                if(preg_match_all('!\d+!', $priceText2, $match)){
                                 	$val=$match[0][0];
                               
                                 $pp=$this->bot->userStorage()->find()->get('goods');
                                
                                    if($val<$pp){
                                        $this->negotiationPart2();
                                       }
                                       elseif($val==$pp||$val>$pp){

                                       	$this->say('I will agree with that let me send you a link for payment ');
                                       	$this->agreeState($val);

                                       	 }
                                 }	else{

                                 	 return $this->repeat('Sorry,am not geeting you,please mention a price');
                                 }


                   }); 

                }


            }else{
             return $this->repeat('Sorry,am not geeting you,please mention a price');
           
            }
      
        });



}


public function negotiationPart2(){
      $dd=$this->bot->userStorage()->find()->get('phone');
	$this->ask('This '.$dd.' is of high quality,that price is not enough', function(Answer $answer) {
            $value= $answer->getText(); 

              if(preg_match_all('!\d+!', $value, $match)){
                                 	$val=$match[0][0];
                               
                                 $pp=$this->bot->userStorage()->find()->get('goods');
                                
                                    if($val<$pp){
                                        $this->negotiationPart3();
                                       }
                                       elseif($val==$pp||$val>$pp){

                                       	$this->say('I will agree with that let me send you a link for payment ');
                                       	$this->agreeState($val);

                                       	 }
                                 }	else{


                                 	 return $this->repeat('Sorry,am not geeting you,please mention a price');
                                 }
            

        });

	
}




public function negotiationPart3(){

	 $dd=$this->bot->userStorage()->find()->get('phone');
	$this->ask('This '.$dd.' is perfect for you , you gonna love this '.$dd.'', function(Answer $answer) {
            $value= $answer->getText(); 

              if(preg_match_all('!\d+!', $value, $match)){
                                 	$val=$match[0][0];
                                
                                 $pp=$this->bot->userStorage()->find()->get('firstprice');
                                
                                    if($val<$pp){
                                        $this->negotiationPart4();
                                       }
                                       elseif($val==$pp||$val>$pp){

                                       	$this->say('I will agree with that let me send you a link for payment ');
                                       	$this->agreeState($val);

                                       	 }
                                 }	else{

                                 	 return $this->repeat('Sorry,am not geeting you,please mention a price');
                                 }
            

        });


}


public function negotiationPart4(){

	 $dd=$this->bot->userStorage()->find()->get('phone');
	 $pp=$this->bot->userStorage()->find()->get('firstprice');

	$this->ask('Ok,but how make it '.$pp.' shillings', function(Answer $answer) {
            $value= $answer->getText(); 

              if(preg_match_all('!\d+!', $value, $match)){
                                 	$val=$match[0][0];
                               
                                 $pp=$this->bot->userStorage()->find()->get('firstprice');
                                
                                    if($val<$pp){
                                        $this->negotiationPart5();
                                       }
                                       elseif($val==$pp||$val>$pp){

                                       	$this->say('I will agree with that let me send you a link for payment ');

                                       	$this->agreeState($val);

                                       	 }
                                 }else{

                                 	 return $this->repeat('Sorry,am not geeting you,please mention a price');
                                 }	
            

        });


}

 
public function  negotiationPart5(){
	  $pp=$this->bot->userStorage()->find()->get('firstprice');
              $finalprice=$pp-50;
	
	 $this->ask('I can offer you a final price of '.$finalprice.' do you agree or no?', function(Answer $answer) {
             $value=$answer->getText(); 
               $pp=$this->bot->userStorage()->find()->get('firstprice');
              $finalprice=$pp-50;
             $this->finalAgreement($value,$finalprice);         
        });
}


public function finalAgreement($value,$finalprice){

	 $str=preg_replace('/\\s/',' ',$value);
	 if (preg_match_all('#\b(agree|I accept|I accept the price|yes)\b#i', $str, $out)){
       $this->say('Ok,let me send you a link for payment');
       $this->agreeState($finalprice);
	  }elseif(preg_match_all('#\b(I dont agree|I dont accept|I dont accept the price|no|do not|nop)\b#i', $str, $out)){

         $this->finalAcceptance($finalprice);
	  }else{

        return $this->repeat('Sorry,am not geeting you,please say if you agree or you dont agree');

	  }


}



public function finalAcceptance($finalprice){

	$this->ask('Ok, i cannot offer you below that one', function(Answer $answer) {
            $value=$answer->getText();
            $this->finalAgreement($value,$finalprice);
        });


}



    public function agreeState($price){
       $amount=$price;
       $email=$this->bot->userStorage()->find()->get('email');
       $phn=$this->bot->userStorage()->find()->get('mobile');
       $asset=$this->bot->userStorage()->find()->get('phone');
       $phne=ltrim($phn,'0');
        
       $phone='254'.$phne;

      $this->say('<a href="http://127.0.0.1/chatbot/payment/mpesa.php?phoneno='.$phone.'&amount='.$amount.'&email='.$email.'&asset='.$asset.' ">PAY NOW</a>');
        }

    /**
     * Start the conversation.
     *
     * @return mixed
     */
    public function run(){

    	$this->askName();
        //
    }
}
