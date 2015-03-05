<?php
$excluded = explode(',', $excl);
if (!empty($albums)):

    foreach ($albums as $key => $album) {
        if (!empty($album['cover_photo'])) {
            $picture = WalleriaUtility::remoteGet('https://graph.facebook.com/' . $album['cover_photo'] . '?fields=source');

            $res = WalleriaUtility::str_lreplace('/', '/' . $code . '/', $picture->source);
            $coverphoto = preg_replace('/_[aso].([^_a.]*)$/', '_n.$1', $res);
        }
        if (!in_array($album['id'], $excluded)):
            if ($album['photos']):
                ?>
                <div  class="fp-mainAlbWrapper fp-main<?php echo $classtext ?>Wrapper">
                    <a data-id="<?php echo $album['id'] ?>" class="fp-albThumbLink fp-alb<?php echo $classtext ?>Link"" data-count="<?php echo $album['count'] ?>" data-click="0" href="<?php echo $album['link'] ?>">
                        <?php
                        foreach ($album['photos']['data'] as $images) {

                            $res = WalleriaUtility::str_lreplace('/', '/' . $code . '/', $images['picture']);
                            $photo = preg_replace('/_[aso].([^_a.]*)$/', '_n.$1', $res);
                            ?>
                            <span class="fp-albThumbWrap fp-alb<?php echo $classtext ?>Thumb"><i style="background-image:url(<?php echo $photo; //$images['images'][$position]['source']   ?>);display:block; "></i></span>
                        <?php } ?>
                   <!-- <span class="fp-albThumbWrap fp-alb<?php echo $classtext ?>Thumb"><i style="background-image:url(<?php //echo "http://graph.facebook.com/".$album->cover_photo."/picture"  ?>);display:block;background-size:100% "></i></span>-->
                        <?php if (!empty($coverphoto)): ?>
                            <span class="fp-albThumbWrap fp-alb<?php echo $classtext ?>Thumb"><i style="background-image:url(<?php echo $coverphoto; ?>);display:block;"></i></span>
                        <?php endif; ?>
                    </a>
                    <span class="fp-albClearFix"></span>
                    <div class="fp-photoDetails fp-photo<?php echo $classtext ?>Details ">
                        <a data-count="<?php echo $album['count'] ?>"  data-id="<?php echo $album['id'] ?>"  class="fp-DescLink fp-Desc<?php echo $classtext ?>Link" href="<?php echo $album['link'] ?>"><?php echo $album['name'] ?></a>
                        <br/><span class="fp-PhotoCount"><?php echo $album['count'] ?> photos</span></div></div>
                <?php
            endif;
        endif;
    }
      
endif;
 