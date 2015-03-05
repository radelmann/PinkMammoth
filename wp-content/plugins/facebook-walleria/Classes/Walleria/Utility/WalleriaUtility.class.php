<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utility
 *
 * @author fchari
 */
class WalleriaUtility {
    //put your code here
    /**
     * Facebook graph endpoint
     */

    const FB_URL = 'https://graph.facebook.com/';

    /**
     * Fetch contents of a remote Url
     * @param string $url
     * @param array $options Optional: array overrides default
     * @return array 
     */
    public static function remoteGet($url, $options = array()) {
        add_filter('http_request_timeout', array('WalleriaUtility', 'moreTime'));
        $raw_response = wp_remote_get($url, $options);
        //print_r($raw_response);
        if (is_wp_error($raw_response) || 200 != wp_remote_retrieve_response_code($raw_response))
            return false;

        $response = json_decode(wp_remote_retrieve_body($raw_response));

        return $response;
    }

    /**
     * Fetch contents of a remote Url
     * @param string $url Url
     * @param array $options Optional: array overrides default
     * 
     * @return array 
     */
    public static function remotePost($url, $options = array()) {
        add_filter('http_request_timeout', 30);
        $raw_response = wp_remote_post($url, $options);

        if (is_wp_error($raw_response) || 200 != wp_remote_retrieve_response_code($raw_response))
            return false;

        $response = json_decode(wp_remote_retrieve_body($raw_response));

        return $response;
    }

    /**
     * This function calculates time difference and returns a 
     * formatted string representation of the the time difference
     * 
     * @param int $current_time Current unix time
     * @param int $previous_time The time to subtract as unix representation
     * 
     * @return string formatted time difference
     *
     */
    public static function formatedTime($current_time, $previous_time) {

        $curtime = $current_time;

        //$oldtime='@'.$previous_time;
        //echo $oldtime;
        //date_default_timezone_set( $timezone=get_option('timezone_string'));
        //date_default_timezone_set('Europe/London');

        $datetime = new DateTime($previous_time);

        $utimezone = new DateTimezone(timezone_name_from_abbr("", round(get_option('gmt_offset')) * 3600, false));

        $datetime->setTimezone($utimezone);
        $oldtime = $datetime;

//string to timestamp
        $previous_time = strtotime($previous_time);
        $dif = $curtime * 1000 - $previous_time * 1000;

        $string = "";
//echo $dif .",";
        //if 1 second
        if ($dif <= 1000) {
            $string = __(" about a second ago", 'facebook-walleria');
        }
        //if dif is less than min show seconds
        if ($dif < 1000 && $dif < 60000) {
            $string = floor($dif / 1000) . __(" seconds ago", 'facebook-walleria');
        }
        //about a min
        if ($dif >= 60000 && $dif < 720000) {
            $string = __(" about a minute ago", 'facebook-walleria');
        }

//if dif is less than hr show min
        if ($dif >= 60000 && $dif < 3600000) {
            $string = floor($dif / 1000 / 60) . __(" minutes ago", 'facebook-walleria');
        }
        //if btwn 1 & 2 hrs
        if ($dif >= 3600000 && $dif < 7200000) {
            $string = __(" about an hour ago", 'facebook-walleria');
        }

        //if dif is less than 1 day show hrs
        if ($dif >= 7200000 && $dif < 86400000) {
            $string = floor($dif / 1000 / 60 / 60) . __(" hours ago", 'facebook-walleria');
        }

//if greater than day but less than week in this year
        if ($dif >= 86400000 && $dif < 604800000) {
            $string = $oldtime->format('l') . __(' at ', 'facebook-walleria') . $oldtime->format('H:i');
        }
        //if greater than week but in this year
        if ($dif >= 604800000 && $dif < 31556952000) {

            $string = $oldtime->format('d F') . __(' at ', 'facebook-walleria') . $oldtime->format('H:i');
            // string=oldtime.toString('d M')+' at ' + oldtime.toString('H:i')
        }
        //if greater than year
        if ($dif > 31556952000) {

            $string = $oldtime->format('d F Y') . __(' at ', 'facebook-walleria') . $oldtime->format('H:i');
        }
        // string=oldtime.toString('dd MMMM yyyy')+' at ' + oldtime.toString('HH:mm')}

        return $string;
    }

    /**
     * Replace links in text with html links
     *
     * @param  string $text
     * @return string
     */
    public static function autoLinkText($text) {
        $text = nl2br($text);
        $pattern = "/(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9._\/~#&=;%+?-]+[a-z0-9\/#=?]{1,1})/is";
        $text = preg_replace($pattern, " <a  href='$1'>$1</a>", $text);
        // fix URLs without protocols
        $text = preg_replace("/href='www/", "href='http://www", $text);
        return $text;
    }

    /**
     * Had to use this because some users are still on PHP >=5.2
     * @return int minutes
     */
    public static function moreTime() {
        return 30;
    }

    /**
     * Replace a string at the last occurence in a string
     * @param string $search
     * @param string $replace
     * @param string $subject
     * @return string
     */
        public static function str_lreplace($search, $replace, $subject) {
        $pos = strrpos($subject, $search);

        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

}
