<?php
/**
 * Gets a single Facebook Album
 *
 * @author fchari
 */
class WalleriaAlbum {

    public $id;
    public $name;
    public $description;
    public $photos;
    public $paging;
    public $settings;
    public $facebook;

    public function __construct($albumid = "", $options = array()) {
        $this->settings=  get_option('walleria');
        $this->facebook = new Facebook_Zoxion_Extend(array('appId' => $this->settings['fwpg_appId'], 'secret' => $this->settings['fwpg_appSecret']));
         //if enable private then set user token
        if ($this->settings['fwpg_enableprivate']) {
            $token = get_option('fwpg_accessToken');
            if (!empty($token['access_token'])) {
                $this->facebook->setAccessToken($token['access_token']);
            }
        }
        if ($albumid != ""){
            $this->getAlbum($albumid, $options);
        }
       
        
    }

    /**
     * Get an album
     * @param type $albumid
     * @param array $options array with token,limit
     */
    private function getAlbum($albumId, $args= array()) { 
        $limit = isset($args['limit']) ? $args['limit'] : 20;
        $page=isset($args['page'])?$args['page']:1;
        if($this->settings['fwpg_cacheTime']!=""){
        $response = json_decode(get_transient($albumId . "_walleria_photos_" . $page), true);
            if (!$response) {
                $response = $response =  $this->facebook->api($albumId . "/photos",'GET',$args);   
        set_transient($albumId . "_walleria_photos_" . $page, json_encode($response), $this->settings['fwpg_cacheTime'] * MINUTE_IN_SECONDS);
        }
        }else{
           $response =  $this->facebook->api($albumId . "/photos",'GET',$args);   
        
        
        }
        $this->photos = $response['data'];
        $this->paging =  isset($response['paging'])?$response['paging']:array();        
    }

    /**
     * Get an album ajax
     * @param type $albumid
     * @param array $options array with token,limit
     */
    public function getAlbumAjax($albumId, $args, $options = array()) { 
      
          $page=isset($args['page'])?$args['page']:1;
        if($this->settings['fwpg_cacheTime']!=""){
            $response = json_decode(get_transient($albumId . "_walleria_photos_" . $page), true);
            if (!$response) {
                $response =$this->facebook->api($albumId . "/photos",'GET',$args);
            set_transient($albumId . "_walleria_photos_" . $page, json_encode($response), $this->settings['fwpg_cacheTime'] * MINUTE_IN_SECONDS);
        }
        }else{
            $response =  $this->facebook->api($albumId . "/photos",'GET',$args); 
        }
        $this->photos = $response['data'];
        $this->paging = isset($response['paging'])?$response['paging']:array();
    }

}
