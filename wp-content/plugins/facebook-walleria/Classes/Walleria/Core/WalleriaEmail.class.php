<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Notice
 *
 * @author fchari
 */
class WalleriaEmail {
    //put your code here
    public $email;
    public $subject;
    public $message;
    public $headers;




    public function __construct(WalleriaMessage $message) {
        $this->email=$message->recepient;
        $this->subject=$message->subject;
        $this->message=$message->message;
        $this->headers='From: WordPress';
      
        $this->sendEmail();
    }
    
    public function sendEmail(){
      
     mail($this->email,  $this->subject,  $this->message,  $this->headers);
   
    }
} 

?>
