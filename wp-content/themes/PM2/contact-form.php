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
        if(empty($_SESSION['6_letters_code'] ) || strcasecmp($_SESSION['6_letters_code'], $_POST['6_letters_code']) != 0){        
	//Note: the captcha code is compared case insensitively.
	//if you want case sensitive match, update the check above to
	// strcmp()
		$hasError = true;
                $captchaErrorMsg = 'Invalid Captcha';
             
	} else {
            $captchaErrorMsg = '';
        }
		//If there is no error, send the email
		if(!isset($hasError)) {

                        //$info_email = get_option('Ag_info_email');
			$emailTo = "contact@pinkmammoth.org"; //$info_email;
			$subject = $subject;
			$sendCopy = trim($_POST['sendCopy']);
			$body = "$name sent you a message via the Pink Mammoth website shown here:  \n\n '$comments'";
			$headers = 'From: PM Website <contact@pinkmammoth.org>' . "\r\n" . 'Reply-To: ' . $email;

			mail($emailTo, $subject, $body, $headers);
                        //mail("ztwbiker@gmail.com", $subject, $body, $headers);

			if($sendCopy == true) {
				$subject = $subject;
				$headers = 'From: PM Website <contact@pinkmammoth.org>';
				mail($emailTo, $subject, $body, $headers);
			}

			$emailSent = true;

		}
	}
} ?>


        <?php get_header();?>
          
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

              
<?php if(isset($emailSent) && $emailSent == true) { ?>
<div class="ContactForm">
                    	<div class="thanks">
                    		<h3>Thanks, <?=$name;?></h3>
                    		<p>Your email was successfully sent. We will be in touch soon. Pink Love!</p>
                    	</div>
</div>
                    <?php } else { ?>
                  <div class="ContactForm">
                    <?php if(isset($hasError) || isset($captchaError)) { ?>
                    <p class="error">It appears that you may have missed something...<p>
                    <?php } ?>
                        
                    
                    		<form action="<?php the_permalink(); ?>" id="contactForm" method="post">

                    		<ol class="forms">
				<label for="contactName"><h3>Name</h3></label>
					<input type="text" name="contactName" id="contactName" size="73"  value="<?php if(isset($_POST['contactName'])) echo $_POST['contactName'];?>" class="requiredField" />
					<?php if($nameError != '') { ?>
						<span class="error"><?=$nameError;?></span>
					<?php } ?><br><br>
                                                

			 <label for="email"><h3>Email</h3></label>
					<input type="text" name="email" id="email-contact"  size="73" class="requiredField email input" value="<?php if(isset($_POST['email']))  echo $_POST['email'];?>" />
					<?php if($emailError != '') { ?>
						<span class="error"><?=$emailError;?></span><br>
					<?php } ?><br><br>
                                                
  			 <label for="subject"><h3>Subject</h3></label>
					<input type="text" name="subject" id="subject"  size="73" class="requiredField subject input" value="<?php if(isset($_POST['subject']))  echo $_POST['subject'];?>"  />
					<?php if($subjectError != '') { ?>
						<span class="error"><?=$subjectError;?></span><br>
					<?php } ?><br><br>
                                                

				<label for="commentsText"><h3>Message</h3></label>
					<textarea name="comments" id="commentsText" rows="20" cols="70" class="requiredField input"><?php if(isset($_POST['comments'])) { if(function_exists('stripslashes')) { echo stripslashes($_POST['comments']); } else { echo $_POST['comments']; } } ?></textarea>
					<?php if($commentError != '') { ?>
						<span class="error"><?=$commentError;?></span><br>
					<?php } ?>

                               <br><br><img src="http://www.pinkmammoth.org/wp-content/themes/PM2/captcha_code_file.php?rand=<?php echo rand(); ?>" id='captchaimg' ><br>
                               <label for='captcha'><h3>Enter the code above here :</h3></label><br>
                               <input id="6_letters_code" name="6_letters_code" type="text"><br>
                    <small>Can't read the image? click <a href='javascript: refreshCaptcha();'>here</a> to refresh</small>
                    <?php if($captchaErrorMsg != '') { ?>
						<span class="error"><?=$captchaErrorMsg;?></span><br>
					<?php } ?>
				<input type="hidden" name="submitted" id="submitted" value="true" /><br><br>
                                <button type="submit" class="input-submit"></button>
			</ol>
                    		</form><br><br><br>
                                        </div>
                    		
                    <?php } ?>
                                        </div>
            <!-- END OF CONTENT -->
<div class="art-layout-cell art-sidebar1">
              <?php get_sidebar('default'); ?>
              <div class="cleared"></div>
            </div>
        </div>
    </div>
</div>
<div class="cleared"></div>
<script language='JavaScript' type='text/javascript'>
function refreshCaptcha()
{
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}
</script>
<?php get_footer(); ?>