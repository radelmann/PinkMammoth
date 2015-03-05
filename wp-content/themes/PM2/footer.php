    <div class="art-footer">
                <div class="art-footer-body">
                <?php get_sidebar('footer'); ?>
                    <a href="http://twitter.com/pinkmammothlove" class='art-rss-tag-icon' title="Pink Mammoth Twitter"></a>
                    <a href="http://www.facebook.com/PinkMammothSF" class='art-fb-tag-icon' title="Pink Mammoth On Facebook"></a>
                            <div class="art-footer-text">
                                <?php  echo do_shortcode(theme_get_option('theme_footer_content')); ?>
                            </div>
                    <div class="cleared"></div>
                </div>
            </div>
    		<div class="cleared"></div>
        </div>
    </div>
    <div class="cleared"></div>
    <p class="art-page-footer"></p>
    <div class="cleared"></div>
</div>
    <div id="wp-footer">
	        <?php wp_footer(); ?>
	        <!-- <?php printf(__('%d queries. %s seconds.', THEME_NS), get_num_queries(), timer_stop(0, 3)); ?> -->
    </div>
</body>
</html>

