<?php
$homeurl = home_url();
$id_help = <<< END
<p>Need help? Okay, do you have a facebook app?</p>
<p><strong>Yes, I do</strong></p>
<ol>
<li>Get a list of your applications from here: <a target="_blank" href="http://www.facebook.com/developers/apps.php">Facebook Application List</a></li>
<li>Select the application you want, then copy and paste the Application ID and Application Secret from there to the boxes below.</li>
</ol>

<p><strong>No, I haven't created an application yet</strong></p>
<ol>
<li>Go here to create it: <a target="_blank" href="//www.facebook.com/developers/createapp.php">Create a facebook app</a></li>
<li>Good, your app is created. Now, make sure it knows where it's used: On the app's page, click "Edit Settings", click "Web Site".
	You should now see "Core Settings". </li>
<li>Your Site_URL is : <strong>{$homeurl}</strong> . Now click "Save Changes". Done!</li>
<li>Get your app id and app secret from here:
<a target="_blank" href="http://www.facebook.com/developers/apps.php">Facebook Application List</a></li>
<li>Select the application you created, then copy and paste the Application ID and Application Secret from there to the boxes below.</li>
</ol>
END;

if (empty($settings['fwpg_appId']) || empty($settings['fwpg_appSecret'])) {
    echo '<div class="error"><p><strong>' . __('Facebook  Walleria will not be able to show wall or private albums unless you add a valid Application ID and Application Secret.') . '</strong></p>' . $id_help . '</div>';
}
?>


<h2><?php _e('Credentials', 'facebook-walleria'); ?> </h2>
<?php //echo $settings[1];
?>
<p><?php
if ($set) {
    echo '<div style="color: #4F8A10;background-color: #DFF2BF; margin:auto; text-align:center;width:50%; border:1px solid yellowgreen;"><p></p>' . __('Facebook Walleria is set up and ready to use !', 'facebook-walleria') . '<p></p></div>';
} else {
    ?><div class="error" style="padding:10px"><?php echo!empty($error) ? $error : "" ?></div>
    <?php } ?>
</p>


<table class="form-table" style="clear:none;">
    <tbody>

        <tr valign="top">
            <th scope="row"><?php _e(sprintf('Facebook Application ID %s Help %s','(<a href="http://zoxion.com/walleria/documentation/#faq4">','</a>)'), 'facebook-walleria'); ?></th>
            <td>
                <fieldset>

                    <label for="appId">
                        <input style="width: 200px;" type="text" name="walleria[fwpg_appId]" id="appId" value="<?php if ($settings['fwpg_appId'] != "") echo $settings['fwpg_appId']; ?>" />
                        <?php _e("Your application's ID ", 'facebook-walleria'); ?>
                    </label><br /><br />
                </fieldset>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php _e(sprintf('Application Secret %s Help %s','(<a href="http://zoxion.com/walleria/documentation/#faq4">','</a>)'), 'facebook-walleria'); ?></th>
            <td>
                <fieldset>
                    <label for="appSecret"> 
                        <input style="width: 200px;" type="text" name="walleria[fwpg_appSecret]" id="appSecret" value="<?php if ($settings['fwpg_appSecret'] != "") echo $settings['fwpg_appSecret']; ?>" />
                        <?php  _e('Your application\'s secret (default:)', 'facebook-walleria'); ?>
                    </label><br /><br />

                </fieldset>
            </td>
        </tr>
       <tr valign="top">
            <th scope="row"><?php  _e(sprintf('Walleria Purchase Code %s Help %s','(<a target="_blank" href="http://zoxion.com/blog/2013/08/12/how-to-get-a-codecanyon-license-key/">','</a>)'), 'facebook-walleria'); ?></th>
            <td>
                <fieldset>

                    <label for="purchaseCode">
                        <input style="width: 200px;" type="text" name="walleria[fwpg_purchaseCode]" id="purchaseCode" value="<?php if ($settings['fwpg_purchaseCode'] != "") echo $settings['fwpg_purchaseCode']; ?>" />
                        (<?php _e('optional','facebook-walleria');?> ) <?php _e('The purchase code for walleria ', 'facebook-walleria'); ?>
                    </label><br /><br />
                </fieldset>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Cache time (<a href="http://zoxion.com">Help</a>)', 'facebook-walleria'); ?></th>
            <td>
                <fieldset>

                    <label for="CacheTime">
                        <select style="width: 200px;" type="text" name="walleria[fwpg_cacheTime]" id="CacheTime"  >
                            <option value="" <?php if ($settings['fwpg_cacheTime'] == "") echo "selected=selected"; ?>>No Caching</option>
                            <option value="1" <?php if ($settings['fwpg_cacheTime'] == 1) echo "selected=selected"; ?>>1</option>
                            <option value="3" <?php if ($settings['fwpg_cacheTime'] == 3) echo "selected=selected"; ?>>3</option>
                            <option value="5" <?php if ($settings['fwpg_cacheTime'] == 5) echo "selected=selected"; ?>>5</option>
                            <option value="10" <?php if ($settings['fwpg_cacheTime'] == 10) echo "selected=selected"; ?>>10</option>
                            <option value="30" <?php if ($settings['fwpg_cacheTime'] == 30) echo "selected=selected"; ?>>30</option>

                        </select>
                        <?php _e('Minutes to cache your data', 'facebook-walleria'); ?>
                    </label><br /><br />
                </fieldset>
            </td>
        </tr>

        <tr>
            <th scope="row"><?php _e('Private User Albums?(<a href="http://zoxion.com">Help</a>)', 'facebook-walleria'); ?></th>
            <td>
                <fieldset>
                    <label for="enablePrivate"> <?php ?>
                        <select style="width: 200px;" type="text" name="walleria[fwpg_enableprivate]" id="enablePrivate" >
                            <option value="0" <?php if ($settings['fwpg_enableprivate'] == 0) { ?> selected=selected <?php } ?>>Disabled</option>
                            <option value="1" <?php if ($settings['fwpg_enableprivate'] == 1) { ?>selected=selected <?php } ?>>Enabled</option>
                        </select>
                        <?php _e('If you intend to fetch private user albums enable this option (default:Disabled)', 'false'); ?>
                    </label><br /><br />

                </fieldset>
            </td>
        </tr>
    </tbody>
</table>