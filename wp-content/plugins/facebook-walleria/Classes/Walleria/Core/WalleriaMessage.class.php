<?php

/**
 * Sets email messages to be used by the Email class
 *
 * @author fchari
 */
class WalleriaMessage {
    public $recepient;
    public $subject;
    public $message;

    //put your code here
    public function __construct($email,$subject="",$message="") {
        $this->recepient=$email;
        if($subject!=""){
           $this->subject=$subject; 
        }
        if($message!=""){
           $this->message=$message; 
        }
    }
    
    /**
     * Set  an Access Token expiry message
     */
    public function setExpiryMessage(){
        $this->message=__('Your Facebook Walleria access token is going to expire soon. Your site will not function well if it expires. Renew: '). admin_url("admin.php?page=walleria");
        $this->subject=__('Facebook Walleria Token about to expire');
    }
    
    /**
     * Set an expired Access Token Message
     */
    public function setExpiredMessage(){
        $this->message=__('Your Facebook Walleria access token is no longer valid, You need to renew it for your site to function properly. Renew: '). admin_url("admin.php?page=walleria");
        $this->subject=__('Facebook Walleria Token has expired');
        
    }
}

?>
