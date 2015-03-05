<?php
/*
Template Name: Contact Form
*/
?>
<?php
//If the form is submitted
if(isset($_POST['submitted'])) {

	//Check to see if the honeypot captcha field was filled in
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {

		//Check to make sure that the name field is not empty
		if(trim($_POST['contactName']) === '') {
			$nameError = 'Please enter your name.';
			$hasError = true;
		} else {
			$name = trim($_POST['contactName']);
		}

		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['email']) === '')  {
			$emailError = 'Please enter your email address.';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
			$emailError = 'You entered an invalid email address.';
			$hasError = true;
		} else {
			$email = trim($_POST['email']);
		}

		//Check to make sure that the name field is not empty
		if(trim($_POST['subject']) === '') {
			$subjectError = 'Please enter your subject.';
			$hasError = true;
		} else {
			$subject = trim($_POST['subject']);
		}
		//Check to make sure comments were entered
		if(trim($_POST['comments']) === '') {
			$commentError = 'Please enter your comments.';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['comments']));
			} else {
				$comments = trim($_POST['comments']);
			}
		}

		//If there is no error, send the email
		if(!isset($hasError)) {

                        //$info_email = get_option('Ag_info_email');
			$emailTo = "ztwbiker@gmail.com"; //$info_email;
			$subject = $subject;
			$sendCopy = trim($_POST['sendCopy']);
			$body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
			$headers = 'From: PM Website <contactform@pinkmammoth.com>' . "\r\n" . 'Reply-To: ' . $email;

			mail($emailTo, $subject, $body, $headers);
                        mail("ztwbiker@gmail.com", $subject, $body, $headers);

			if($sendCopy == true) {
				$subject = $subject;
				$headers = 'From: PM Website <contactform@pinkmammoth.com>';
				mail($emailTo, $subject, $body, $headers);
			}

			$emailSent = true;

		}
	}
} ?>


        <?php get_header();?>
          <script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/contact-form.js"></script>
            <!-- BEGIN PAGE TITLE -->
             <div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
			<?php get_sidebar('top'); ?>
			<?php
				if(have_posts()) {

					/* Start the Loop */
					while (have_posts()) {
						the_post();
						get_template_part('content', 'contact-form');
						/* Display comments */
						if ( theme_get_option('theme_allow_comments')) {
							comments_template();
						}
					}

				} else {

					 theme_404_content();

				}
		    ?>
			<?php get_sidebar('bottom'); ?>
              <div class="cleared"></div>
            </div>
            <div class="art-layout-cell art-sidebar1">
              <?php get_sidebar('default'); ?>
              <div class="cleared"></div>
            </div>
        </div>
    </div>
</div>
<div class="cleared"></div>
<?php if(isset($emailSent) && $emailSent == true) { ?>

                    	<div class="thanks">
                    		<h3><?php _e('Thanks,','wpml-theme');?> <?=$name;?></h3>
                    		<p><?php _e('Your email was successfully sent. I will be in touch soon.','wpml-theme');?></p>
                    	</div>

                    <?php } else { ?>

                    	<?php if (have_posts()) : ?>

                    	<?php while (have_posts()) : the_post(); ?>
                    		<?php the_content(); ?>

                    		<?php if(isset($hasError) || isset($captchaError)) { ?>
                    			<p class="error"><?php _e('There was an error submitting the form.','wpml-theme');?><p>
                    		<?php } ?>

                    		<form action="<?php the_permalink(); ?>" id="contactForm" method="post">

                    			<ol class="forms">
				<li><label for="contactName"><?php _e('Name','wpml-theme');?></label>
					<input type="text" name="contactName" id="contactName" value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="requiredField" />
					<?php if($nameError != '') { ?>
						<span class="error"><?=$nameError;?></span>
					<?php } ?>
				</li>

			 <li><label for="email"><?php _e('Email','wpml-theme');?></label>
					<input type="text" name="email" id="email-contact" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" class="requiredField email input" />
					<?php if($emailError != '') { ?>
						<span class="error"><?=$emailError;?></span>
					<?php } ?>
				</li>
  			 <li><label for="subject"><?php _e('Subject','wpml-theme');?></label>
					<input type="text" name="subject" id="subject" value="<?php if(isset($_POST['subject']))  echo $_POST['subject'];?>" class="requiredField subject input" />
					<?php if($subjectError != '') { ?>
						<span class="error"><?=$subjectError;?></span>
					<?php } ?>
				</li>

				<li class="textarea"><label for="commentsText"><?php _e('Inquiry','wpml-theme');?></label>
					<textarea name="comments" id="commentsText" rows="20" cols="30" class="requiredField input"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
					<?php if($commentError != '') { ?>
						<span class="error"><?=$commentError;?></span>
					<?php } ?>
				</li>
				<li class="screenReader"><label for="checking" class="screenReader">If you want to submit this form, do not enter anything in this field</label><input type="text" name="checking" id="checking" class="screenReader" value="<?php if(isset($_POST['checking']))  echo $_POST['checking'];?>" /></li>
				<li class="buttons"><input type="hidden" name="submitted" id="submitted" value="true" /><button type="submit" class="input-submit"></button></li>
			</ol>
                    		</form>

                    		<?php endwhile; ?>
                    	<?php endif; ?>
                    <?php } ?>


                  	 </div>
                 </div>
                 

                 </div>
            </div>
            <!-- END OF CONTENT -->

            <?php get_footer();?>