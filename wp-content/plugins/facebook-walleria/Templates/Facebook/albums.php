<?php ?>

<div id="<?php echo $count . "_" . $id ?>"  data-size="<?php echo strtolower($classtext) ?>" data-previous="<?php echo $previous ?>" data-next="<?php echo $next ?>" data-paging="<?php echo $paging ?>" data-id="<?php echo $id ?>" data-toggle="<?php echo $toggle ?>" data-limit="<?php echo $limit ?>" data-excl="<?php echo $excl; ?>" class="fp-container fp-Clear">
    <div class="fp-PhotoContainer fp-Hide" data-page="1" >
        <div class="fp-PhotoContainerHeader">
            <h1 class="fp-AlbumHeader"></h1>
            <a href="" class="fp-Remove"></a>

        </div>
        <div class="fp-photoContainerBody"></div>
        <div   class="fp-ShowAlbums fp-Clear">
            <i></i> 
            <span class="fp-BackToAlbums fp-ImgBlockContent"><?php _e('Back to Albums', 'fwpg') ?></span>
        </div>
    </div>
    <div class="fp-albumContainerWrap">
        <div class="fp-AlbumContainer fp-Clear">
            <?php
            $excluded = !empty($excl) ? explode(',', $excl) : array();
            if (!empty($albums)):
                foreach ($albums as $key => $album) {
                    if (!empty($album['cover_photo'])) {
                        $picture = WalleriaUtility::remoteGet('https://graph.facebook.com/' . $album['cover_photo'] . '?fields=source');

                        $res = WalleriaUtility::str_lreplace('/', '/' . $code . '/', $picture->source);
                        $coverphoto = preg_replace('/_[aso].([^_a.]*)$/', '_n.$1', $res);
                    }
                    if (!in_array($album['id'], $excluded)):
                        if (isset($album['photos'])):
                            ?>
                            <div  class="fp-mainAlbWrapper fp-main<?php echo $classtext ?>Wrapper">
                                <a data-id="<?php echo $album['id'] ?>" class="fp-albThumbLink fp-alb<?php echo $classtext ?>Link" data-count="<?php echo $album['count'] ?>" data-click="0" href="<?php echo $album['link'] ?>">
                                    <?php
                                    foreach ($album['photos']['data'] as $images) {
                                        $res = WalleriaUtility::str_lreplace('/', '/' . $code . '/', $images['picture']);
                                        $photo = preg_replace('/_[aso].([^_a.]*)$/', '_n.$1', $res);
                                        ?>
                                        <span class="fp-albThumbWrap fp-alb<?php echo $classtext ?>Thumb"><i style="background-image:url(<?php echo $photo ?>); "></i></span>
                                    <?php } ?>
                                    <?php if(!empty($coverphoto)): ?>
                                    <span class="fp-albThumbWrap fp-alb<?php echo $classtext ?>Thumb"><i style="background-image:url(<?php echo $coverphoto; ?>);display:block;"></i></span>
                                <?php endif; ?>
                            </a>
                            <span class="fp-albClearFix"></span>
                            <div class="fp-photoDetails fp-photo<?php echo $classtext ?>Details ">
                                <a data-count="<?php echo $album['count'] ?>" data-id="<?php echo $album['id'] ?>" class="fp-DescLink fp-Desc<?php echo $classtext ?>Link" href="<?php echo $album['link'] ?>"><?php echo $album['name'] ?></a>
                                <br/><span class="fp-PhotoCount"><?php echo $album['count'] ?> photos</span></div></div>
                        <?php
                    endif;
                    endif;
                }
            endif;
            ?>

        </div>
        <?php
        if (isset($scroll) && $scroll == true) {
            if (!empty($cursors['after'])):
                ?>
                <div class="fp-BottomBar" data-size="<?php echo strtolower($classtext) ?>" data-limit="<?php echo $limit; ?>"  data-page="1" data-cursors="<?php echo htmlentities(json_encode(array('after' => $cursors['after']))) ?>" data-id="<?php echo $id ?>" ><span class="fp-Loadmore" style="margin: 0 5px"><img src="<?php echo WALLERIA_URL ?>/images/down-pointer.png" /><span id="fp-LoadMoreWall"><?php echo __('Show more', 'fwpg') ?></span></span></div>

                <?php
            endif;
        }else {
            ?>
            <div data-page="1" style="<?php
        if (empty($cursors['before'])) {
            echo "display:none";
        }
            ?>" class="left-scroll" data-cursors="<?php echo!empty($cursors['before']) ? htmlentities(json_encode(array('before' => $cursors['before']))) : ''; ?>" data-limit="<?php echo $paging; ?>"  data-size="<?php echo strtolower($classtext) ?>"></div>
            <div data-page="1" style="<?php
             if (empty($cursors['after'])) {
                 echo "display:none";
             }
             ?>" class="right-scroll" data-cursors="<?php echo!empty($cursors['before']) ? htmlentities(json_encode(array('after' => $cursors['after']))) : ''; ?>" data-limit="<?php echo $paging; ?>"  data-size="<?php echo strtolower($classtext) ?>"></div>
<?php } ?>
    </div>
</div>