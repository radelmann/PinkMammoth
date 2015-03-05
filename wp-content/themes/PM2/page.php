<?php get_header(); ?>
<div class="art-layout-wrapper">
    <div class="art-content-layout">
        <div class="art-content-layout-row">
            <div class="art-layout-cell art-content">
                <?php get_sidebar('top'); ?>
                <?php
                //echo "page template.";
                if (have_posts ()) {

                    /* Start the Loop */
                    while (have_posts ()) {
                        the_post();
                        get_template_part('content', 'page');
                        //check for address meta
                        $address = get_post_meta(get_the_ID(), 'Address', true);
                        //$address = str_replace(' ', '+', $address);
                        if ($address)
                        {
                            echo embedGMap($address);
                        }
                ?>

                        
                <?php
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

            function embedGMap($address) {

                    // First, setup the variables you will use on your <iframe> code
                    // Your Iframe will need a Width and Height set
                    // as well as the address you plan to Iframe
                    // Don't forget to get a Google Maps API key
                    $iframe = '';
                    $latitude = '';
                    $longitude = '';
                    $iframe_width = '100%';
                    $iframe_height = '200px';
                    //$address = 'ENTER YOUR ADDRESS HERE';
                    $address = urlencode($address);
                    //$key = "YOUR GOOGLE MAPS API KEY";
                    //$url = "http://maps.google.com/maps/geo?q=" . $address . "&output=json&key=" . $key;
                    $url = "http://maps.google.com/maps/geo?q=" . $address . "&output=json";
                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
                    // Comment out the line below if you receive an error on certain hosts that have security restrictions
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                    $data = curl_exec($ch);
                    curl_close($ch);

                    $geo_json = json_decode($data, true);

                    // Uncomment the line below to see the full output from the API request
                    // var_dump($geo_json);
                    // If the Json request was successful (status 200) proceed
                    if ($geo_json['Status']['code'] == '200') {

                        $latitude = $geo_json['Placemark'][0]['Point']['coordinates'][0];
                        $longitude = $geo_json['Placemark'][0]['Point']['coordinates'][1];

                        $iframe = '<iframe width="' . $iframe_width . '" height="' . $iframe_height . '" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="';
                        $iframe.= 'http://maps.google.com/maps';
                        $iframe.= '?f=q';
                        $iframe.= '&amp;source=s_q';
                        $iframe.= '&amp;hl=en';
                        $iframe.= '&amp;geocode=';
                        $iframe.= '&amp;q=' . $address;
                        $iframe.= '&amp;aq=0';
                        $iframe.= '&amp;ie=UTF8';
                        $iframe.= '&amp;hq=';
                        $iframe.= '&amp;hnear=' . $address;
                        $iframe.= '&amp;t=m';
                        $iframe.= '&amp;ll=' . $longitude . ',' . $latitude;
                        $iframe.= '&amp;z=16';
                        $iframe.= '&amp;iwloc=';
                        $iframe.= '&amp;output=embed"></iframe>';
                        
                    } else {
                        $iframe = "<p>No Address Available</p>";
                    }

                    $iframe = '<div style="margin-top:0px;margin-left:5px;margin-bottom:15px;">' . $iframe . '</div>';
                    return $iframe;
            }
?>
