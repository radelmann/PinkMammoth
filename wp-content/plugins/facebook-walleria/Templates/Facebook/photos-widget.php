<?php
if (sizeof($args) > 0) {
    extract($args, EXTR_SKIP);
    $fws_title = apply_filters('widget_title', empty($instance['fws_title']) ? '' : $instance['fws_title'], $instance);
    $fws_albumid = ($instance['fws_album'] != '') ? $instance['fws_album'] : '';
    $fws_fblink = ($instance['fws_fblink'] != '') ? $instance['fws_fblink'] : '';
    $fws_number = ($instance['fws_number'] != '') ? $instance['fws_number'] : '2';
    $fws_border = ($instance['fws_border'] != '') ? $instance['fws_border'] : '#94a3c4';
    $fws_text_color = ($instance['fws_text_color'] != '') ? $instance['fws_text_color'] : '#3b5998';
    $fws_links = ($instance['fws_links'] != '') ? $instance['fws_links'] : '#3B5998';
    $fws_body_bg = ($instance['fws_body_bg'] != '') ? $instance['fws_body_bg'] : '#fff';
    $fws_width = ($instance['fws_width'] != '') ? $instance['fws_width'] : '250px';
    $fws_height = ($instance['fws_height'] != '') ? $instance['fws_height'] : '400px';
    $fws_size = ($instance['fws_size'] != '') ? $instance['fws_size'] : 'medium';
}
if (!empty($photos)) {
    shuffle($photos);
}
?>
<div class="fp-WidgetPhotoWrap" style="width:<?php echo $fws_width ?>;  background:<?php echo $fws_body_bg ?>; color:<?php echo $fws_text_color ?>; border:<?php echo $fws_border; ?>">
    <?php if ($fws_title != "") { ?>
        <h3 class="fp-WidgetTitle"><?php echo$fws_title ?></h3>
    <?php } ?>
    <?php
    foreach ($photos as $key => $photo) {
        $link = 'http://facebook.com/' . $photo['from']['id'];

        if (isset($photo['name'])) {
            $name = $photo['name'];
        } else {
            $name = '';
        }
        $jsobject[] = array('href' => $photo['images'][$position]['source'], 'title' => WalleriaUtility::autoLinkText($name), 'fbowner' => $photo['from']['id'], 'fbid' => $photo['id']);
        $res = WalleriaUtility::str_lreplace('/', '/' . $code . '/', $photo['picture']);
        $pic = preg_replace('/_[aso].([^_a.]*)$/', '_n.$1', $res);
        if ($key < $fws_number) {
            ?>
            <div class="fp-PhotoThumbWrap">
                <a id="" class="fp-PhotoThumbLink fp-WidgetPhoto fp-Photo<?php echo $classtext ?>Thumb" data-from="<?php echo $photo['from']['id'] ?>" data-id="<?php echo $photo['id'] ?>" href="<?php echo $photo['source'] ?>" rel="<?php echo $fws_albumid ?>[fp-gallery]" title="<?php echo $name ?>">
            <?php if ($settings['fwpg_gallery'] == 'Photoswipe') { ?>
                        <span><img src="<?php echo $pic; ?>"/></span>
            <?php } else { ?>
                        <i style="background-image:url(<?php echo $pic ?>)"></i>
            <?php } ?>
                </a></div>
            <?php
        }
    }


    if ($fws_fblink == "Yes") {
        ?>
        <div class="fp-WidgetFbLink"><a href="<?php echo $link ?>" target="_blank"><i></i>View on Facebook</a></div>
<?php
}
$json = json_encode($jsobject);
?>            
    <i class="jsondata" data-json="<?php echo htmlentities($json) ?>"></i></div>

