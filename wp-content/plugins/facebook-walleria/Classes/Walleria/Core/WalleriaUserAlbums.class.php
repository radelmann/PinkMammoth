<?php

/**
 * Represents a user's albums
 *
 * @author fchari
 */
class WalleriaUserAlbums {

    public $id;
    public $name;
    public $description;
    public $albums;
    public $paging;
    public $settings;
    public $facebook;

    public function __construct($userId = "", $options = array()) {
        $this->settings = get_option('walleria');
        $this->facebook = new Facebook_Zoxion_Extend(array('appId' => $this->settings['fwpg_appId'], 'secret' => $this->settings['fwpg_appSecret']));

        //if enable private then set user token
        if ($this->settings['fwpg_enableprivate']) {
            $token = get_option('fwpg_accessToken');
            if (!empty($token['access_token'])) {
                $this->facebook->setAccessToken($token['access_token']);
            }
        }
        if ($userId != "")
            $this->getAlbums($userId, $options);
    }

    /**
     * Get an album
     * @param type $albumid
     * @param array $options array with token,limit
     */
    private function getAlbums($userId, $options = array()) {
        $limit = isset($options['limit']) ? $options['limit'] : 2;
        $tokenText = isset($options['token']) ? "&access_token={$options['token']}" : "";
        $page = isset($options['page']) ? $options['page'] : 1;
        if ($this->settings['fwpg_cacheTime'] != "") {

            $response = json_decode(get_transient($userId . "_walleria_albums_" . $page), true);
            if (!$response) {
                $response = $this->facebook->api("$userId?fields=albums.limit($limit).fields(name,cover_photo,count,link,photos.limit(4))$tokenText", 'GET', $options);
                //json encode because transients fail to serializE some special characters
                set_transient($userId . "_walleria_albums_" . $page, json_encode($response), $this->settings['fwpg_cacheTime'] * MINUTE_IN_SECONDS);
            }
        } else {
            $response = $this->facebook->api("$userId?fields=albums.limit($limit).fields(name,cover_photo,count,link,photos.limit(4))$tokenText", 'GET', $options);
        }
        $this->id = $response['id'];
        $this->albums = $response['albums']['data'];
        $this->paging = $response['albums']['paging'];
    }

    /**
     * Get an album
     * @param type $albumid
     * @param array $options array with token
     */
    public function getAlbumsAjax($userId, $args = array()) {
        $page = isset($args['page']) ? $args['page'] : 1;
        if ($this->settings['fwpg_cacheTime'] != "") {
            $response = json_decode(get_transient($userId . "_walleria_albums_" . $page), true);
            if (!$response) {
                $response = $this->facebook->api("$userId/albums?fields=name,cover_photo,count,link,photos.limit(4)", 'GET', $args);
                set_transient($userId . "_walleria_albums_" . $page, json_encode($response), $this->settings['fwpg_cacheTime'] * MINUTE_IN_SECONDS);
            }
        } else {
            $response = $this->facebook->api("$userId/albums?fields=name,cover_photo,count,link,photos.limit(4)", 'GET', $args);
        }
        $this->albums = $response['data'];
        $this->paging = isset($response['paging']) ? $response['paging'] : array();
    }

    /**
     * Get an album
     * @param type $albumid string of comma separated album ids
     * @param array $options array with tokpen,limit
     */
    public function getSpecificAlbums($albumIds, $args = array()) {

        $page = isset($args['page']) ? $args['page'] : 1;
        if ($this->settings['fwpg_cacheTime'] != "") {
            $response = json_decode(get_transient($albumIds . "_specalbums_" . $page), true);
            if (!$response) {
                $response = $this->facebook->api("?ids=$albumIds&fields=name,cover_photo,count,link,photos.limit(4)", 'GET');
                set_transient($albumIds . "_specalbums_" . $page, $response, $this->settings['fwpg_cacheTime'] * MINUTE_IN_SECONDS);
            }
        } else {
            $response = $this->facebook->api("?ids=$albumIds&fields=name,cover_photo,count,link,photos.limit(4)", 'GET');
        }
        $this->albums = $response;
    }

}
