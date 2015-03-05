<?php
/*
Template Name: Events
*/
?>

 <?php get_header();?>

        <!-- <script type="text/javascript" src=" --> <?php //bloginfo('template_directory'); ?> <!-- /js/contact-form.js"></script> -->
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
						get_template_part('content', 'events');
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
                
                <div class="art-postcontent"><div>

<?php
echo 'events page id:' . $post->ID;
$child_pages = $wpdb->get_results("SELECT *  FROM $wpdb->posts WHERE post_parent = ".$post->ID."   AND post_type = 'page' ORDER BY menu_order", 'OBJECT'); ?>

<?php if ( $child_pages ) : foreach ( $child_pages as $pageChild ) : setup_postdata( $pageChild );?>
 <h2><a class="event_header_link" href="<?php echo  get_permalink($pageChild->ID); ?>" rel="bookmark" title="<?php echo $pageChild->post_title; ?>"><?php echo $pageChild->post_title; ?></a></h2>
<?php
echo "<div class='event'><br><hr class='event_hr'><br>";
if ( has_post_thumbnail($pageChild->ID)) {
      $post_thumbnail_id = get_post_thumbnail_id( $pageChild->ID );
      // replace 'thumbnail' with the image size you want to show
      
      $args = array(
	'post_type' => 'attachment',
	'post_status' => null,
	'post_parent' => $pageChild->ID,
	'include'  => $post_thumbnail_id
	);

   $thumbnail_image = get_posts($args);

   if ($thumbnail_image && isset($thumbnail_image[0])) {
     //show thumbnail title
     //echo $thumbnail_image[0]->post_title;

     //Uncomment to show the thumbnail caption
     //echo $thumbnail_image[0]->post_excerpt . "<br>";

     //Uncomment to show the thumbnail description
     //echo $thumbnail_image[0]->post_content;

     //Uncomment to show the thumbnail alt field
     //$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
     //if(count($alt)) echo $alt;
  }
  echo '<table cellpadding=0 cellspacing=0><tr>';
  echo '<td rowspan="2">';
  echo '<a href="' . get_permalink($pageChild->ID) . '">';
  $image_attributes = wp_get_attachment_image_src( $post_thumbnail_id, 'thumbnail' ); // returns an array
      echo '<img class="event_thumbnail" src="'.$image_attributes[0].'" />';
      echo '</a>';
      echo '</td>';
}
echo '<td valign="top" style="padding-left:15px">';

//the_excerpt();
the_excerpt();
//the_content();

//theme_get_content();

echo '</td>';
echo '</tr>';
echo '<tr><td align="right" style="padding-left:10px"><a class="event_readmore" href="'.get_permalink($pageChild->ID).'"></a></tr></td>';
echo '</table>';
echo "<br><br>";
?>
<?php echo "</div>"; endforeach; endif; ?>
                </div></div>


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
<?php get_footer(); ?>