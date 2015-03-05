<?php

include_once(WALLERIA_ABSPATH . 'Admin/Classes/WalleriaToken.class.php');
// Get array with all the options
    $set = true;
    $settings = get_option('walleria');
    $fwpg_accessToken = get_option('fwpg_accessToken');
    $access_token = is_array($fwpg_accessToken) ? $fwpg_accessToken['access_token'] : "";
    $token = new WalleriaToken();
    if (empty($settings['fwpg_appId'])) {
        $error = 'Enter Facebook App Id';
        $set = false;
    } elseif (empty($settings['fwpg_appSecret'])) {
        $error = 'Enter Facebook App Secret';
        $set = false;
    } elseif ($settings['fwpg_enableprivate']) {
        $set = false;
        if (false === $token->testAccessToken($access_token)) {
    //if the app id and secret are set get access token


        $credentials['app_id'] = $settings['fwpg_appId'];
        $credentials['app_secret'] = $settings['fwpg_appSecret'];
        $credentials['my_url'] = admin_url('admin.php?page=walleria');


        // known valid access token stored in a database 
        //$access_token = get_option('fwpg_accessToken');

        $code = isset($_REQUEST["code"]) ? $_REQUEST["code"] : "";

        // If we get a code, it means that we have re-authed the user 
        //and can get a valid access_token. 
        if (isset($code) && $code != "") {
            $params = $token->getAccessToken($code, $credentials);

            $access_token = $params['access_token'];
        }


        $response = $token->testAccessToken($access_token);

        //Check for errors 
        if (false === $response) {
//     echo'<div class="error">'.$decoded_response->error->message.'</div>';
            // check to see if this is an oAuth error:
            //if ($decoded_response->error->type== "OAuthException"){
            // Retrieving a valid access token. 
            $dialog_url = "https://www.facebook.com/dialog/oauth?"
                    . "client_id=" . $credentials['app_id']
                    . "&scope=read_stream,publish_stream,friends_photos,friends_videos,offline_access,manage_pages,user_photos,user_videos"
                    . "&redirect_uri=" . $credentials['my_url'];
            echo("<script> 
          if(confirm('You are now leaving your site to get permissions at Facebook, ensure that your App ID and App Secret are valid before proceeding. You get this message at first setup or when your access token expires. ')){
          top.location.href='" . $dialog_url
            . "'}</script>");
            $error = "Access Token could not be authenticated. Check that  App Id , App Secret and your Site url are as they appear on Facebook";
        } else {

            $params['name'] = $response->name;
//      
//      $token_expiry=$params['expires'];
//      $params['expirydate']=time()+$token_expiry;
//      $scheduleTenDay=time()+($token_expiry-864000);
//      $scheduleFiveDay=time()+($token_expiry-432000);
//      
//      /**
//       * Schedule notices
//       */
//      wp_schedule_single_event($scheduleTenDay, 'tendaynotice');
//      wp_schedule_single_event($scheduleFiveDay, 'fivedaynotice');
//      /**
//       * Schedule daily token check
//       */
//      wp_clear_scheduled_hook('daily-token-check');
//       wp_schedule_event(time(), 'daily', 'daily-token-check');

            delete_transient('fwpg_access_token');
            update_option('fwpg_accessToken', $params);
            $set = true;
        }
    } else {
        $set = true;
    }
}

    // Make selects data
    $closePositionArray = array('left', 'right');
    $overlayArray = array(0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1);
    $msArray = array(0, 25, 50, 75, 100, 200, 300, 400, 500, 600, 700, 800, 900, 1000, 1250, 1500, 1750, 2000);
    $easingArray = array('easeInQuad', 'easeOutQuad', 'easeInOutQuad', 'easeInCubic', 'easeOutCubic', 'easeInOutCubic', 'easeInQuart', 'easeOutQuart',
        'easeInOutQuart', 'easeInQuint', 'easeOutQuint', 'easeInOutQuint', 'easeInSine', 'easeOutSine', 'easeInOutSine', 'easeInExpo',
        'easeOutExpo', 'easeInOutExpo', 'easeInCirc', 'easeOutCirc', 'easeInOutCirc', 'easeInElastic', 'easeOutElastic', 'easeInOutElastic',
        'easeInBack', 'easeOutBack', 'easeInOutBack', 'easeInBounce', 'easeOutBounce', 'easeInOutBounce');
    $titlepos = array('outside', 'inside', 'over');
