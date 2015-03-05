<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WalleriaShortcodes
 *
 * @author fchari
 */
class WalleriaShortcodes {
  
    
    public function __construct() {
        add_shortcode('fpphotos', array($this,'embedPhotos'));
        add_shortcode('fpalbums', array($this,'embedUserAlbums'));
        add_shortcode('fpspecificalbums', array($this,'embedSpecificAlbums'));
        add_shortcode('fpwall', array($this,'embedFeed'));
        
    }

    /**
 * embed photos in an album
 * 
 * @staticvar int $count
 * @param string $album
 * @return string
 */
function embedPhotos($album) {

    static $count = 0;
    $count++;
    extract(shortcode_atts(array(
                'id' => '',
                'limit' => 20,
                'size'=>'large',
                'noscroll'=>false
                    ), $album));
    
    $code=Walleria::embedAlbum($id, $limit,$size,$noscroll);
    return($code);

    
} 


/**
 * embed user albums
 * 
 * @staticvar int $count
 * @param string $userid
 * @return string 
 */
public function embedUserAlbums($userid) {

    static $count = 0;
    $count++;
    extract(shortcode_atts(array(
                'id' => 'cocacola',
                'limit' => 10,
                'paging' => 25,
                'size'=>'large',
                'scroll'=>true,
                'toggle'=>true,
                'excl'=>''
                    ), $userid));
   
    $code=Walleria::embedUserAlbums($id, $limit,$paging,$size,$scroll,array('toggle'=>$toggle,'excl'=>$excl));
    return($code);
   
}


/**
 * embed specific albums
 * 
 * @staticvar int $count
 * @param string $userid
 * @return string 
 */
public function embedSpecificAlbums($ids) {

    static $count = 0;
    $count++;
    extract(shortcode_atts(array(
                'id' => '101113351469',
                'paging' => 25,
                'size'=>'large',
                'toggle'=>false
                    ), $ids));
   
    $code=Walleria::embedSpecificAlbums($id,$paging,$size,array('toggle'=>$toggle));
    return($code);
   
}
/**
 * embed specific albums
 * 
 * @staticvar int $count
 * @param string $userid
 * @return string 
 */
public function embedFeed($objectId) {

    static $count = 0;
    $count++;
    extract(shortcode_atts(array(
                'id' => 'audi',
                'limit' => 15,
                'type'=>'posts'
                    ), $objectId));
   
    $code=Walleria::embedFeed($id,$limit,array('type'=>$type));
    return($code);
   
}
}

?>
