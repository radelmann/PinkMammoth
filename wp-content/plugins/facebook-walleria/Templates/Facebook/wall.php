<div id="wall_<?php echo $count . "_" . $wallid ?> "    data-id="<?php echo $wallid ?> " data-url="<?php echo $url ?> " class="fp-WallContainer">
   <!-- <div class="fp-WallBar">
        <h3>Wall</h3>
    </div>-['

    <div class="fp-Composer"><?php //echo $tabs ?> </div>-->
    <ul class="fp-ProfileStream">
        <?php
        /*
         * To change this template, choose Tools | Templates
         * and open the template in the editor.
         */
        if (!empty($feed)):
            foreach ($feed as $x => $item) {               // print_r($item);
                $typeclass = "";
                $playbutton="";
                $linkimage = "";
                
                if (!isset($item['story']) || (isset($item['story']) && $item['type'] != 'status')):
                    $photo = false;
                    $islink = false;
                    $video = false;
                    $actorphoto = "https://graph.facebook.com/{$item['from']['id']}/picture";
                    $message = nl2br($item['message']);
                    $actions=isset($item['actions'])?$item['actions']:array();
                    $link = isset($item['actions'][0]['link'])?$item['actions'][0]['link']:'';
                    $name = $item['from']['name'];
                    $caption =isset($item['caption'])?$item['caption']:"";
                    $description = isset($item['description'])?$item['description']:"";
                    $time = $item['created_time'];
                    $icon = $item['icon'];
                    $commentoffset=isset($item['comments']['data'])?count($item['comments']['data']):0;
                   
                    /**
                     * Handle photos
                     */
                    if(isset($item['object_id']) && $item['type'] !='video'){
                    $bigpic =preg_replace('/_s.([^_s.]*)$/', '_n.$1', $item['picture']);
                    $picture = preg_replace('/\/hphotos.*?\//', '$0s600x600/', $bigpic);
                    }else{
                      $picture="";  
                    }
                    /**
                     * Handle videos from Facebook and External
                     */
                    if (isset($item['type']) && $item['type'] == 'video') {
                        $video = true;
                        $href = $item['source'];
                        $typeclass = "fp-WallVideoThumb";
                        $playbutton = "<i></i>";
                        $vidid;
                        $fwfb_vid;
                        $linkimage = isset($item['object_id']) ? "https://graph.facebook.com/{$item['object_id']}/picture":$item['picture'];
                        if (isset($item['application'])) {
                            if ($item['application']['name'] == 'Video') {
                                $vidid = explode("_", $item['id']);
                                $vidid = $vidid[1];
                                $fwfb_vid = "http://www.facebook.com/v/" . $vidid;
                            } else {
                                $fwfb_vid = $item['source'];
                            }
                        } else {
                            if ($item['object_id']) {
                                $vidid = $item['object_id'];
                                $fwfb_vid = "http://www.facebook.com/v/" . $vidid;
                            } else {
                                $fwfb_vid = $item['source'];
                            }
                        }
                    }
                    if (isset($item['type']) && $item['type'] == 'link') {
                        $islink = true;
                        $href = $item['link'];
                        $typeclass = "fp-WallLinkThumb";
                        $linkimage = $item['picture'];
                        $playbutton = "";
                    }

                    //if there are likes we check if there is detail of likers in the data attr otherwise we just show count.
                    if (isset($item['likes'])) {
                        $likebar;
                        $likers = "";
                        $count = "";
                        $comma = "";
                        if ($item['likes']) {
                            if ($item['likes']['count'] > 1) {
                                $count = __('and ', 'facebook-walleria') . $item['likes']['count'] . __(' others like this', 'facebook-walleria');
                            } else {
                                $count = __(" likes this", 'facebook-walleria');
                            }
                            for ($i = 0; $i < count($item['likes']['data']); $i++) {
                                if ($i < count($item['likes']['data']) - 1) {
                                    $comma = ",";
                                }

                                $likers .=' <a href="http://www.facebook.com/profile.php?id=' . $item['likes']['data'][$i]['id'] . '">' . $item['likes']['data'][$i]['name'] . '</a>' . $comma;
                            }
                        } else {
                            if ($item['likes']['count'] > 1) {
                                $count = $item['likes']['count'] . __(' people like this', 'facebook-walleria');
                            } else {
                                $count = __(" likes this", 'facebook-walleria');
                            }
                        }
                    } else {
                        $likebar = "";
                    }

                    /**
                     * Handle story and story tags
                     */
                    $story = "";
                    if (isset($item['story'])) {
                        $story = $item['story'];
                    }
                    ?>

                    <li   class="fp-StreamWrapper fp-Clear">
                        <div class="topBorder"></div>
                        <div class="fp-innerStreamContent">
                            <div class="fp-StreamHeader fp-Clear">
                                <a class="fp-ActorPhoto fp-BlockImage">
                                    <img src="<?php echo $actorphoto ?> "/>
                                </a>
                                <div class="fp-ActorName">
                                    <a href="http://www.facebook.com/profile.php?id=<?php echo $item['from']['id'] ?> "><?php echo $item['from']['name'] ?> </a> <span class="fp-story"><?php echo $story ?></span>
                                </div>
                                <span class="fp-Gray"><?php echo WalleriaUtility::formatedTime(time(), $time) ?></span>
                            </div>         
                            <span class="fp-Message"><?php echo WalleriaUtility::autoLinkText( $message) ?></span>

                            <?php if ($picture): 
                                 $rel = $settings['fwpg_gallery'] == 'PrettyPhoto' ? 'fpgallery[' . $wallid . ']' : $wallid . 'fpgallery';
                                ?>
                                <div class="fp-PhotoLink fp-Clear">
                                    <a class="fp-WallPhoto" target="_blank" class="<?php echo $typeclass ?> " href="<?php echo $picture ?> " title="<?php echo $message ?> "  rel="<?php echo $rel ?>" data-id="<?php echo $id ?> " data-inline="<?php echo $fwfb_vid ?> " ><img class="fp-wallPic" src="<?php echo $picture ?> " title="<?php echo $message ?>"><?php echo $playbutton ?> </a>
                                </div>
                            <?php endif; ?>

                            <?php if ($video || $islink) : ?>
                                <div class="fp-Attachment fp-Clear">
                                    <?php if ($linkimage): ?>             
                                        <a target="_blank" class="<?php echo $typeclass ?> " href="<?php echo $href ?> " rel="" data-id="<?php echo $id ?> " data-inline="<?php echo $fwfb_vid ?> " ><img class="fp-wallPic" src="<?php echo $linkimage ?> " rel="a[x]" alt="a" title="a"><?php echo $video?$playbutton:"" ?> </a>
                                    <?php endif; ?> 
                                    <div class="fp-MetaDetail">
                                        <div class="fp-MetaTitle">
                                            <strong>
                                                <span>
                                                    <a target="_blank" href="<?php echo $link ?>"><?php echo $name ?></a>
                                                </span>
                                            </strong>
                                        </div>
                                        <span class="fp-MetaCaption"><?php echo $caption ?></span>
                                        <span class="fp-MetaDescription"><?php echo WalleriaUtility::autoLinkText($description) ?></span>
                                        <?php echo $properties ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="fp-CommentShareBtn fp-Clear">
                                <i style="background-image: url(<?php echo $icon ?> )"></i>
                                <div class="fp-ActionDeck">
                                    <span class="fp-DateRep" data-time="<?php echo $time ?> ">
                                        <a href="<?php echo $post_link ?> "><?php //echo fwpg_output_time(time(), $time)  ?> </a>
                                    </span>
                                    <span> <?php //echo $application ?> </span>
                                    <?php for ($i = 0; $i < count($actions); $i++) { ?>
                                        <a class="fp-Post<?php echo $actions[$i]['name'] ?>" data-name="<?php echo $caption ?>"  data-id="<?php echo $item['id'] ?>" href="<?php echo $item['actions'][$i]['link'] ?>">
                                            <?php echo $actions[$i]['name']; ?>
                                        </a> 
                                    <?php } ?>
                                    <span class="fp-LinkActionDeck"><?php //echo $actions ?> </span>
                                </div>
                            </div>
                            <div class="fp-PostFooterBox fp-Clear"><?php
                        if (isset($item['likes'])):
                                        ?>
                                    <div class="fp-TinyTopPointer">
                                        <i></i>
                                    </div>
                                    <div class="fp-LikesCountWrapper">
                                        <div class="fp-LikesCount fp-Clear">
                                            <a class="fp-LikeHandIcon" href=""></a>
                                            <div class="fp-ImgBlockContent">
                                                <?php echo $likers . ' ' . $count ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                                //if plural, if single comment or no comment
                                if (isset($item['comments']['paging']['next']) !== ''): 
                                    ?>
                                    <div class="fp-CommentsBar fp-FooterItemWrapper fp-Clear">
                                        <i></i>
                                        <div class="fp-ImgBlockContent">
                                            <a class="fp-ViewPrevious fp-LoadComments" data-id="<?php echo $item['id'] ?> " data-args="<?php echo htmlentities(json_encode($item['comments']['paging']['cursors'])); ?>" data-page="1"   href="<?php echo $link ?> "><?php  _e("View more comments","fwpg")?></a>
                                        </div>
                                    </div>
                                <?php  
                                else:
                                endif;
                                ?> 
                                <?php
//get comments
                                if (isset($item['comments']['data'])):
                                    ?>
                                    <ul class="fp-CommentsBody">
                                        <?php
                                        for ($a = 0; $a < count($item['comments']['data']); $a++) {
                                            $profphoto = "https://graph.facebook.com/{$item['comments']['data'][$a]['from']['id']}/picture?width=32&height=32";
                                            ?>    
                                            <li class="fp-FooterItemWrapper fp-CommentItem">
                                                <div class="fp-ImgBlockWrapper fp-Clear">
                                                    <a href="http://www.facebook.com/profile.php?id='<?php echo $item['comments']['data'][$a]['from']['id'] ?>" class="fp-BlockImage fp-ProfilePhotoAnchor">
                                                        <img class=" fp-ProfilePhotoMedium" width="32" height="32" src="<?php echo $profphoto ?>"/>
                                                    </a>
                                                    <div class="fp-ImgBlockContent fp-CommentDiv fp-Clear" data-id="<?php echo $item['comments']['data'][$a]['id'] ?>">
                                                        <div class="fp-ActorName">
                                                            <a href="http://www.facebook.com/profile.php?id='<?php echo $item['comments']['data'][$a]['from']['id'] ?>">
                                                                <?php echo $item['comments']['data'][$a]['from']['name'] ?>
                                                            </a>
                                                        </div>
                                                        <div class="fp-CommentSpan"><?php echo $item['comments']['data'][$a]['message'] ?>
                                                        </div>
                                                        <span data-time="<?php echo $item['comments']['data'][$a]['created_time'] ?>" class="fp-DateRep fp-Clearfix"><?php echo WalleriaUtility::formatedTime(time(), $item['comments']['data'][$a]['created_time']) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                    <?php
                                else:
                                    ?>
                                    <ul class="fp-CommentsBody"></ul>
                                <?php
                                endif;
                                ?>
                                    <div class="fp-FooterItemWrapper fp-CommentBox">
                                    <div class="fp-ImgBlockWrapper fp-Clear">
                                        <img class="fp-BlockImage fp-CommenterImg" src=""/>
                                        <div class="fp-ImgBlockContent fp-BeforeTxt">
                                            <div class="fp-TextAreaWrap">
                                                <textarea data-id="<?php echo $item['id'] ?>" autocomplete="off" rows="1" class="fp-PreComment"><?php echo 'Write a comment...' ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 </div>         
                        </div>
                        <div class="bottomBorder"></div>
                    </li> 

                    <?php
                endif;
            }
        //endif empty
        endif;
        ?>

</ul>
    <?php 
if($paging){
?>
<div class="fp-BottomBar" data-type="<?php echo $objecttype ?>" data-page="1" data-cancomment="<?php echo  $cancomment ?>" data-args="<?php echo $paging ?>" data-id="<?php echo  $wallid ?>" ><span class="fp-Loadmore" style="margin: 0 5px"><img src="<?php echo  WALLERIA_URL ?>/images/down-pointer.png" /><span id="fp-LoadMoreWall"><?php echo  __('Show more', 'fwpg') ?></span></span></div>
<?php
}
?>
</div>
