<?php
if(!empty($photos)):
foreach ($photos as $key => $photo) {
              $res= WalleriaUtility::str_lreplace('/', '/'.$code.'/', $photo['picture']);
              $pic=preg_replace('/_[aso].([^_a.]*)$/', '_n.$1',$res);
                          
                        $rel = $settings['fwpg_gallery'] == 'PrettyPhoto' ? 'fpgallery[' . $id . ']' : $id . 'fpgallery';
                        ?>
            <div class="fp-PhotoThumbWrap">
                <a id="" target="_blank" class="fp-PhotoThumbLink fp-PhotoThumbnail fp-Photo<?php echo $classtext ?>Thumb" data-cancomment="" data-viewonfb="<?php  echo $showfblink ?>" data-fburl="<?php  echo $photo['link'] ?>" data-from="<?php  echo $photo['from']['id'] ?>" data-id="<?php  echo $photo['id'] ?>" href="<?php  echo $photo['source'] ?>" rel="<?php  echo $rel ?>" title="<?php  echo $photo['name'] ?>">
                    <?php if($settings['fwpg_gallery'] =='Photoswipe'){?>
                    <span><img src="<?php echo $pic; ?>"/></span>
             <?php }else{?>
                   <i style="background-image:url(<?php echo $pic ?>)"></i>
                   <?php } ?> </a>
            </div>
                <?php
                } 
            endif;
               ?>

<?php if(isset($cursors['cursors']['after'])){ ?>
<div class="fp-BottomBar" data-page="<?php echo $page; ?>" data-size="<?php echo strtolower($classtext) ?>" data-args="<?php echo htmlentities(json_encode($cursors['cursors']))?>" data-id="<?php echo  $id ?>" ><span class="fp-Loadmore" style="margin: 0 5px"><img src="<?php echo  WALLERIA_URL ?>/images/down-pointer.png" /><span id="fp-LoadMoreWall"><?php echo  __('Show more', 'fwpg') ?></span></span></div>
<?php }