<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WalleriaTasks
 *
 * @author fchari
 */
class WalleriaTasks {
  
    
    
public function scheduleTokenExpiryNotice(){
    
        //Notify Admin
          $email= get_bloginfo('admin_email');
          $message= new WalleriaMessage($email);
          $message->setExpiryMessage(); 
          new WalleriaEmail($message);  
    
    //Notify Token owner
      
}

public  static function cronTokenCheck(){
    $credentials=  get_option('fwpg_accessToken');
    // Attempt to query the graph:
  $graph_url = "https://graph.facebook.com/me?fields=name,email&access_token=" . $credentials['access_token'];
 
  $response=  WalleriaUtility::remoteGet($graph_url);
  
  if ( false===$response){
     //Notify Admin
          $email= get_bloginfo('admin_email');
          $message= new WalleriaMessage($email);
          $message->setExpiredMessage(); 
          new WalleriaEmail($message);               
  }
			
  
  
}

}

?>
