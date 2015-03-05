<?php
if(!empty($comments)):
   foreach ($comments as $comment){
      
       $item=$comment;
       $message=$item['message'] !==''? nl2br($item['message']):"";
       $time=$item['created_time'] !==''? $item['created_time']:"";
       ?>
      <li class="fp-FooterItemWrapper fp-CommentItem">
          <div class="fp-ImgBlockWrapper fp-Clear">
              <a href="http://www.facebook.com/profile.php?id=<?php echo $item['from']['id']?> " class="fp-BlockImage"><img class=" fp-ProfilePhotoMedium" width="32" height="32" src="http://graph.facebook.com/<?php echo $item['from']['id']?>/picture?width=32&height=32"/></a>
              <div class="fp-ImgBlockContent" data-id="<?php echo $item['id']?> ">
                  <div class="fp-ActorName"><a href="http://www.facebook.com/profile.php?id=<?php echo $item['from']['id']?> "><?php echo $item['from']['name']?> </a>
                  </div>
                  <div class="fp-CommentSpan "><?php echo $message?> </div>
                  <span data-time="<?php echo $time?> " class="fp-DateRep"><?php echo WalleriaUtility::formatedTime(time(),$time )?> </span>
              </div>
          </div>
         </li>
      <?php
    }
    endif;
