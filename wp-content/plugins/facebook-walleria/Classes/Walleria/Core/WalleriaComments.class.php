<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Get Comments
 *
 * @author fchari
 */
class WalleriaComments {
    
    public $id;
    public $name;
    public $paging;
    public $comments;
    public $facebook;
    public $settings;


    public function __construct($objectId,$options=array()) {
       $this->settings = get_option('walleria');
       $this->facebook = new Facebook_Zoxion_Extend(array('appId' => $this->settings['fwpg_appId'], 'secret' => $this->settings['fwpg_appSecret']));
       $this->getComments($objectId, $options);
    }
    
    /**
     * Get an album
     * @param type $albumid
     * @param array $options array with token,limit
     */
    private function getComments($objectId,$options=array()){ 
        unset($options['before']);
        //$queryString=isset($options['querystring'])?$options['querystring']:"";
        $tokenText=isset($options['token'])?"&access_token={$options['token']}":"";
        //$response=  WalleriaUtility::remoteGet(WalleriaUtility::FB_URL.$objectId."/comments?{$queryString}{$tokenText}");
        $response = $this->facebook->api("$objectId/comments", 'GET', $options);
        $this->comments=$response['data'];
        $this->paging=isset($response['paging'])?$response['paging']:'';
    }
    
}

?>
