<?php
if (sizeof($args) > 0) {
    extract($args, EXTR_SKIP);
    $fws_username = ($instance['fws_username'] != '') ? $instance['fws_username'] : 'codebyfreeman';
    $fws_title = apply_filters('widget_title', empty($instance['fws_title']) ? '' : $instance['fws_title'], $instance);
    $fws_fblink = ($instance['fws_fblink'] != '') ? $instance['fws_fblink'] : '';
    $fws_number = ($instance['fws_number'] != '') ? $instance['fws_number'] : '8';
    $fws_border = ($instance['fws_border'] != '') ? $instance['fws_border'] : '#94a3c4';
    $fws_text_color = ($instance['fws_text_color'] != '') ? $instance['fws_text_color'] : '#3b5998';
    $fws_links = ($instance['fws_links'] != '') ? $instance['fws_links'] : '#3B5998';
    $fws_body_bg = ($instance['fws_body_bg'] != '') ? $instance['fws_body_bg'] : '#fff';
    $fws_width = ($instance['fws_width'] != '') ? $instance['fws_width'] : '250px';
    $fws_height = ($instance['fws_height'] != '') ? $instance['fws_height'] : '400px';
    }
    
    //
        
?>
<div class="fp-StatusWidget fp-Clear" style="width:<?php echo $wallid?>; background:<?php echo $fws_body_bg ?>; color:<?php echo $fws_text_color ?>;border:<?php echo $fws_border ?>">
<?php if ($fws_title != "") { ?>
        <h3 class="fp-WidgetTitle"><?php echo $fws_title ?></h3>

<?php } ?>
    <div class="fp-StatusesWrap" style="width:<?php echo $fws_width ?>"  >
    <?php
    if (!empty($feed)):
        foreach ($feed as $i => $item) { 
           if(in_array($item['type'], array('photo','status'))&& empty($item['story'])):
            $actorphoto = 'https://graph.facebook.com/' . $item['from']['id'] . "/picture";
            $message = isset($item['message']) ? WalleriaUtility::autoLinkText($item['message']): "";
            $type = $item['type'];
            $link = 'https://facebook.com/' . $item['from']['id'];
            $id = $item['from']['id'];
            $time = $item['updated_time']; 
           
            
            // $properties=$item->properties;
            ?>        
                <div  class="fp-Status fp-Clear" ><a href="http://facebook.com/profile.php?id=<?php echo $id ?>" class="fp-ActorPhoto fp-BlockImage"><img src="<?php echo $actorphoto ?>"/></a><div class="fp-innerStreamContent"><div class="fp-StreamHeader"><div class="fp-ActorName"><a style="color:<?php echo $fws_links ?>"href="http://www.facebook.com/profile.php?id=<?php echo $item['from']['id'] ?>"><?php echo $item['from']['name'] ?></a></div>
                            <?php if ($message) { ?>
                                <span class="fp-Message"><?php echo $message ?></span></div>
                        <?php } else { ?></div>
                        <?php } ?>
                    <?php if ($type == "photo") { 
                         $res = WalleriaUtility::str_lreplace('/', '/p320x320/', $item['picture']);
                         $pic = preg_replace('/_[aso].([^_a.]*)$/', '_n.$1', $res); 
                         $source= str_replace('p320x320/', '', $pic);
                        ?>
                        <div class="fp-PhotoThumbWrap ">
                            <a id="" class="fp-PhotoThumbLink fp-WallPhotoThumb " style="" data-from="<?php echo $item['from']['id'] ?>" data-id="<?php echo $item['id'] ?>" href="<?php echo $source ?>" rel="<?php echo $wallid ?>[fp-gallery]" title="<?php echo $name ?>">
                                <?php if ($settings['fwpg_gallery'] == 'Photoswipe') { ?>
                                    <span><img src="<?php echo $pic; ?>"/></span>
                                <?php } else { ?>
                                    <i style="background-image:url(<?php echo $pic ?>); width:<?php  echo $fws_width;?>; height:<?php  echo $fws_width;?>; "></i>
                                <?php } ?>
                            </a></div>   
                    <?php } ?>
                    <span data-time="<?php echo $time ?>" class="fp-DateRep"><a href="http://facebook.com/profile.php?id=<?php echo $id ?>"><?php echo WalleriaUtility::formatedTime(time(), $time) ?></a></span></div></div>
            <?php
             endif;
        }
       
    endif;
    ?>
</div>
    <?php if ($fws_fblink == "Yes") { ?>
    <div class="fp-WidgetFbLink"><a href="<?php echo $link ?>" target="_blank"><i></i>View on Facebook</a></div>
<?php } ?>
</div>