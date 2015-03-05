<?php

/**
 * Description of WalleriaToken
 *
 * @author fchari
 */
class WalleriaToken {
    public $token;
   
    /**
     * 
     * @param type $code
     * @param type $credentials array with my_url-the redirect_url, app_id, app_secret
     * @return null|boolean
     */
    public function getAccessToken($code,$credentials){
       
    $token_url="https://graph.facebook.com/oauth/access_token?client_id="
      . $credentials['app_id']. "&redirect_uri=" . $credentials['my_url']
      . "&client_secret=" . $credentials['app_secret']
      ."&scope=email,read_stream,publish_stream,friends_photos,friends_videos,manage_pages,user_photos,user_videos"
      . "&code=" . $code . "&display=popup";
    
    $response = $this->remoteGet($token_url);
    
   if(false!==$response){
    $params = null;
    parse_str($response, $params);
    return $params;
   }
   return false;
    }
    

  /**
   * Test the access token
   * @param string $accessToken Facebook AccessToken
   */
  public function testAccessToken($accessToken){
      		
  // Attempt to query the graph:
  $graph_url = "https://graph.facebook.com/me?fields=name,email&access_token=" . $accessToken;
 
  $response=$this->remoteGetJson($graph_url);
  
  if ( false===$response){
   return false;                    
  }
			
  return $response;
  
  }
  
/**
 * Fetch contents of a remote Url
 * @param string $url
 * @param array $options Optional: array overrides default
 * @return array 
 */
private function remoteGetJson($url,$options=array()) { 
    
    add_filter('http_request_timeout',array($this,'moreTime'));
    $raw_response = wp_remote_get($url,$options);
      if (( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response )) ){
          
         if(is_object($raw_response)){
           echo "<div class='error'>" .$raw_response->get_error_message."</div>";  
         }
         if(is_array($raw_response)){
             $err=json_decode($raw_response['body']);
             echo "<div class='error'>" .$err->error->message."</div>";
         }
            return false;
    }elseif($raw_response['response']['code']!=200 ){
       
         echo "<div class='error'>" .$raw_response['response']['message']."</div>";
         return false;
    }
    $response = json_decode( wp_remote_retrieve_body( $raw_response ) );

    return $response;
}
/**
 * Fetch contents of a remote Url
 * @param string $url
 * @param array $options Optional: array overrides default
 * @return array 
 */
private function remoteGet($url,$options=array()) { 
    add_filter('http_request_timeout',array($this,'moreTime'));
    $raw_response = wp_remote_get($url,$options);
   
    if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) ){
        //print_r($raw_response);
         // echo "<div class='error'>" .$raw_response['body']."</div>";
        return false;
    }
    $response = wp_remote_retrieve_body( $raw_response ) ;

    return $response;
}


/**
 * Had to use this because some users are still on PHP >=5.2
 * @return int minutes
 */
public  function moreTime(){
    return 30;
}
}

?>
