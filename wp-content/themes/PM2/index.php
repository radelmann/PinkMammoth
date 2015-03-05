<?php get_header(); ?>
<?php
if (function_exists('wp_content_slider')) {
    wp_content_slider();
}
?>

<div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
                <?php getPodoIFrame(); ?>
                <?php get_sidebar('top'); ?>

<?php get_sidebar('bottom'); ?>
                <div class="art-postcontent"><div>

                        <?php $child_pages = $wpdb->get_results("SELECT *  FROM $wpdb->posts WHERE post_parent = 348 AND post_type = 'page' ORDER BY menu_order", 'OBJECT'); ?>

                        <?php
                            $count = count($child_pages);
                            $i=0;
                            if ($child_pages) : foreach ($child_pages as $pageChild) : setup_postdata($pageChild); ?>
                        <h1><a class="event_header_link" href="<?php echo get_permalink($pageChild->ID); ?>" rel="bookmark" title="<?php echo $pageChild->post_title; ?>"><?php echo strtoupper($pageChild->post_title); ?></a></h1>
                        <?php
                                echo "<div class='event'><br>";
                                if (has_post_thumbnail($pageChild->ID)) {
                                    $post_thumbnail_id = get_post_thumbnail_id($pageChild->ID);
                                    // replace 'thumbnail' with the image size you want to show
                                    $i=$i+1;
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
                                    $image_attributes = wp_get_attachment_image_src($post_thumbnail_id, 'thumbnail'); // returns an array
                                    echo '<img class="event_thumbnail" src="' . $image_attributes[0] . '" />';
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
                                echo '<tr><td align="right" style="padding-left:10px"><a class="event_readmore" href="' . get_permalink($pageChild->ID) . '"></a></tr></td>';
                                echo '</table>';
                                echo "<br>";
                        ?>
                        <?php
                                //no line for last post
                                if ($i!=$count)
                                {
                                    echo '<hr class="event_hr">';
                                }
                                //echo "<br>";
                                echo "</div>";
                            endforeach;
                        endif;
                        ?>
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

<?php
function getPodoIFrame()
{
    
    $feed_url = "http://pinkmammoth.podomatic.com/rss2.xml";
    $feed_entry_path = "http://pinkmammoth.podomatic.com/entry/";
    $content = file_get_contents($feed_url);
    $xml = new SimpleXmlElement($content);
    $permlink = $xml->channel->item[0]->comments;
    $entry = str_replace($feed_entry_path, '', $permlink);
    $html = "<iframe height='85' width='620' frameborder='0' marginheight='0' marginwidth='0' scrolling='no' src='http://pinkmammoth.podomatic.com/embed/frame/posting/".$entry."?json_url=http%3A%2F%2Fpinkmammoth.podomatic.com%2Fentry%2Fembed_params%2F".$entry."%3Fcolor%3Df495b9%26autoPlay%3Dfalse%26width%3D620%26height%3D85%26objembed%3D0' allowfullscreen></iframe>";
    
    $html = '<iframe width="100%" height="166" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Ftracks%2F112440729&amp;color=f495b9&amp;auto_play=false&amp;show_artwork=true"></iframe>';

$html='<div class="art-postcontent"><h1><font color="#FF47A0">FOLLOW PINK MAMMOTH ON SOUNDCLOUD</font></h1><iframe src="https://w.soundcloud.com/player/?url=http%3A%2F%2Fapi.soundcloud.com%2Fplaylists%2F4985394%3Fsecret_token%3Ds-9rCxs&amp;color=f63d83&amp;auto_play=false&amp;show_artwork=true" frameborder="no" scrolling="no" width="100%" height="166"></iframe></div><br><hr class="event_hr">';

    echo $html;
}
?>