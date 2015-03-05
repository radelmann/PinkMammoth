<?php
/*
  Template Name: Participants
 */
?>

<?php get_header(); ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/contact-form.js"></script>
<!-- BEGIN PAGE TITLE -->

<div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
                <?php get_sidebar('top'); ?>
                <?php
                if (have_posts ()) {

                    /* Start the Loop */
                    while (have_posts ()) {
                        the_post();
                        get_template_part('content', 'participants');
                        /* Display comments */
                        if (theme_get_option('theme_allow_comments')) {
                            comments_template();
                        }
                    }
                } else {

                    theme_404_content();
                }
                ?>
                <?php get_sidebar('bottom'); ?>

                <div class="participants"><ul>
<?php $child_pages = $wpdb->get_results("SELECT *  FROM $wpdb->posts WHERE post_parent = " . $post->ID . "   AND post_type = 'page' ORDER BY menu_order", 'OBJECT'); ?>
<?php
                if ($child_pages) : foreach ($child_pages as $pageChild) : setup_postdata($pageChild);
                        echo "<li class='participant'>";
                        if (has_post_thumbnail($pageChild->ID)) {
                            $post_thumbnail_id = get_post_thumbnail_id($pageChild->ID);
                            // replace 'thumbnail' with the image size you want to show

                            $args = array(
                                'post_type' => 'attachment',
                                'post_status' => null,
                                'post_parent' => $pageChild->ID,
                                'include' => $post_thumbnail_id
                            );

                            $thumbnail_image = get_posts($args);

                            if ($thumbnail_image && isset($thumbnail_image[0])) {
                                //show thumbnail title
                                //echo $thumbnail_image[0]->post_title;
                                //Uncomment to show the thumbnail description
                                //echo $thumbnail_image[0]->post_content;
                                //Uncomment to show the thumbnail alt field
                                //$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                                //if(count($alt)) echo $alt;
                            }
                            echo '<a href="' . get_permalink($pageChild->ID) . '">';
                            $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail'); // returns an array
                            echo '<img class="participant_image" src="' . $image_attributes[0] . '" />';
                            echo '</a>';
                        }
//the_excerpt();
//echo "<br><br>";
//Uncomment to show the thumbnail caption
//echo "<br>". $thumbnail_image[0]->post_excerpt . "<br>";
?>
                        <h3><a class="participant_link" href="<?php echo get_permalink($pageChild->ID); ?>" rel="bookmark" title="<?php echo $pageChild->post_title; ?>"><?php echo strtoupper($pageChild->post_title); ?></a></h3>
                        <?php
                        echo "<span class='participant_thumb_capt'>" . $thumbnail_image[0]->post_excerpt . "</span><br>";
                        echo "</li>";
                    endforeach;
                endif; ?>
                    </ul></div></div></div></div>

</div>
<div class="cleared"></div>
                        <?php get_footer(); ?>