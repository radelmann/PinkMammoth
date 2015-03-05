<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Wall
 *
 * @author fchari
 */
class WalleriaWall {

    public $id;
    public $stream;
    public $paging;
    public $settings;
    public $type;
    public $facebook;

    public function __construct($objectId = "", $options = array()) {
        $this->settings = get_option('walleria');
        $this->facebook = new Facebook_Zoxion_Extend(array('appId' => $this->settings['fwpg_appId'], 'secret' => $this->settings['fwpg_appSecret']));
         //if enable private then set user token
        if ($this->settings['fwpg_enableprivate']) {
            $token = get_option('fwpg_accessToken');
            if (!empty($token['access_token'])) {
                $this->facebook->setAccessToken($token['access_token']);
            }
        }
        if ($objectId != "")
            $this->getStream($objectId, $options);
    }

    /**
     * Retrieve a feed from facebook a wall
     *
     * 
     * @param string $userid Facebook User Id or alias
     * @param int $n  number of albums to show
     * 
     * @return string html to embed the albums
     *
     */
    public function getStream($objectId, $options) {
        $limit = isset($options['limit']) ? $options['limit'] : 5;
        $page = isset($options['page']) ? $options['page'] : 1;
        $type = isset($options['type']) ? $options['type'] : 'posts';
        if ($this->settings['fwpg_cacheTime'] != "") {
            $response = json_decode(get_transient($objectId . "_walleria_wall_" . $type . "_" . $page), true);
            if (!$response) {
                    $response = $this->facebook->api("$objectId/$type", 'GET', $options);
                set_transient($objectId . "_walleria_wall_" . $type . "_" . $page, json_encode($response), $this->settings['fwpg_cacheTime'] * MINUTE_IN_SECONDS);
            }
        } else {
            $response = $this->facebook->api("$objectId/$type", 'GET', $options);
            
        }
        $this->stream = $response['data'];
        $this->paging = $response['paging'];
        $this->id = $objectId;
        $this->type = $type;
    }

    /**
     * Retrieve a feed from facebook a wall
     *
     * 
     * @param string $userid Facebook User Id or alias
     * @param args arguments strings with limit, offset etc for 
     * @param array $options Array with token key
     * 
     * @return string html to embed the albums
     *
     */
    public function getStreamAjax($objectId, $args, $options) {
        $tokenText = isset($options['token']) ? "&access_token={$options['token']}" : "";
        $page = isset($options['page']) ? $options['page'] : 1;
        $type = isset($options['type']) ? $options['type'] : 'posts';
        if ($this->settings['fwpg_cacheTime'] != "") {
            $response = json_decode(get_transient($objectId . "_walleria_wall_" . $type . "_" . $page), true);
            if (!$response) {
                $response =  $this->facebook->api("$objectId/$type", 'GET', $args);
                set_transient($objectId . "_walleria_wall_" . $type . "_" . $page, json_encode($response), $this->settings['fwpg_cacheTime'] * MINUTE_IN_SECONDS);
            }
        } else {
            $response = $this->facebook->api("$objectId/$type", 'GET', $args);
        }
        $this->stream = $response['data'];
        $this->paging = $response['paging'];
        $this->id = $objectId;
    }

}
