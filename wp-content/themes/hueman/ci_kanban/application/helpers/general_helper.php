<?php

//================================================================================================================================================================
// CDN
//================================================================================================================================================================
function cdn()
{
    return 'http://cdn.decate.no';
}

function cdn_decate()
{
    return 'http://likables.no/images';
}

function marketplace_photo($photo, $data, $size)
{
    $ci = & get_instance();
    if(!$photo) return FALSE;
    $urlPath = ($ci->framework->isSSL ? 'https://' . $ci->config->item('domain') . 'cdn' : cdn());
    return $urlPath . '/marketplace/thumbs/' . $photo->uploader . '/' . $photo->name . '/' . $photo->name . $size . '.' . $data->ext;
}



function photo($uploader, $photo_name, $ext, $size)
{
    $ci = & get_instance();
    return $ci->config->item('base_url') . 'cdn/images/thumbs/' . $uploader . '/' . $photo_name . '/' . $photo_name . '_' . $size . '.' . $ext;
}


function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'r', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}


function chat_photo($photo, $data, $size)
{
    return cdn() . '/chat/photos/thumbs/' . $photo->uploader . '/' . $photo->name . '/' . $photo->name . $size . '.' . $data->ext;
}




function tv_photo($videoData, $size, $linkify = FALSE)
{
    $imgLocName = $videoData->id . '_' . $videoData->yt_videoId;

    if($linkify)
        return '<a href="/tv/' . $videoData->id . '"><img src="' . cdn() . '/videos/thumbs/' . $videoData->id . '/' . $imgLocName . '/' . $imgLocName . '_' . $size . '.jpg"></a>';
    else
        return cdn() . '/videos/thumbs/' . $videoData->id . '/' . $imgLocName . '/' . $imgLocName . '_' . $size . '.jpg';
}






//================================================================================================================================================================
// POST/GET/strings
//================================================================================================================================================================
function lang($line)
{
    $ci = & get_instance();
    return $ci->lang->line($line);
}

function langWithVar($line, $var)
{
    $ci = & get_instance();
    return sprintf($ci->lang->line($line), $var);
}

function langWith2Var($line, $var1, $var2)
{
    $ci = & get_instance();
    return sprintf($ci->lang->line($line), $var1, $var2);
}

function langWith3Var($line, $var1, $var2, $var3)
{
    $ci = & get_instance();
    return sprintf($ci->lang->line($line), $var1, $var2, $var3);
}

function generateRandomString($length = 5)
{
    return substr(md5(time() . microtime() . rand() . rand()), 0, $length);
}

function containsEmptyFields($array)
{
    foreach($array as $key => $val)
    {
        if(!$val)
            return $key;
    }

    return FALSE;
}

function isJson($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}


function appendHttpToString($string)
{
    if(!strlen($string)) return $string;
    $isHttp  = (substr($string, 0, 7) == 'http://');
    $isHttps = (substr($string, 0, 8) == 'https://');
    return ($isHttp || $isHttps ? $string : 'http://' . $string);
}




//================================================================================================================================================================
// Date/time functions
//================================================================================================================================================================
function getCurrentDateTime()
{
    return date("Y-m-d H:i:s");
}

function formatDateTime($datetime)
{
    return date("d/m H:i", strtotime($datetime));
}

function formatDate($datetime)
{
    return date("d/m/y", strtotime($datetime));
}

function getCurrentDate()
{
    return date("Y-m-d");
}

function convertDate_dayMonthYearToDate($day, $month, $year)
{
    $day    =	mysql_real_escape_string($day);
    $month  =	mysql_real_escape_string($month);
    $year   =	mysql_real_escape_string($year);

    return $year.'-'.$month.'-'.$day;
}

function convertDate_dateToAge($date)
{
    $age    = (empty($date) ? 0 : floor((time() - strtotime($date))/31556926));
    if(is_numeric($age))
        return $age;
    else
        return FALSE;
}

function daysDifferenceBetween2Dates($date1, $date2)
{
    return intval(abs(strtotime($date2) - strtotime($date1)) / 86400);
}

function getTimeDifferenceInMinutes($from, $to = FALSE)
{
    # $from = Past date
    # $to = future date

    if(!$from)  return FALSE;
    if(!$to)    $to = strtotime(getCurrentDateTime());
    $from       = strtotime($from);

    $timeSince  = round(abs($to - $from) / 60,2);
    return (is_numeric($timeSince) ? $timeSince : FALSE);
}


function convertToHoursMins($time) {
    settype($time, 'integer');
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    $hours = (strlen($hours)<2?'0':'').$hours;
    $minutes = (strlen($minutes)<2?'0':'').$minutes;
    return $hours . ':' . $minutes;
}


function nicetime($dateTime, $long = TRUE, $hideLongTime = TRUE, $now = FALSE)
{
    if($long)
    {
        $periods         = array("sekund", "minutt", "time", "dag", "uke", "måned", "år", "tiår");
        $periods2        = array("sekunder", "minutter", "timer", "dager", "uker", "måneder", "år", "tiår");
    }
    else
    {
        $periods         = array("s", "m", "t", "d", "u", "m", "å", "t");
        $periods2        = array("s", "m", "t", "d", "u", "m", "å", "t");
    }

    $lengths         = array("60","60","24","7","4.35","12","10");
    $now             = time();
    $unix_date       = strtotime($dateTime);
    $difference      = $now - $unix_date;
    $tense           = "siden";

    if($hideLongTime && $difference > 691200) return 'mer enn en uke siden';

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if($difference > 1) {
        $periods[$j] = $periods2[$j];
    }


    return ($long ? "$difference $periods[$j] {$tense}" : "$difference$periods[$j]");
}




function days_until($date){
    return (isset($date)) ? floor((strtotime($date) - time())/60/60/24) : FALSE;
}





function nicetime_analytics($dateTime, $long = TRUE, $hideLongTime = TRUE)
{
    if($long)
    {
        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "tiår");
        $periods2        = array("seconds", "minutes", "hours", "days", "weeks", "months", "year", "tiår");
    }
    else
    {
        $periods         = array("s", "m", "t", "d", "u", "m", "å", "t");
        $periods2        = array("s", "m", "t", "d", "u", "m", "å", "t");
    }

    $lengths         = array("60","60","24","7","4.35","12","10");
    $now             = strtotime('2013-12-17 02:00:00');
    $unix_date       = strtotime($dateTime);
    $difference      = $now - $unix_date;
    $tense           = "";

    if($difference > 691200)
    {
        $date1 = $dateTime;
        return floor(($now - strtotime($date1))/86400) . ' days';
    }


    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
    $difference = round($difference);
    if($difference > 1) {
        $periods[$j] = $periods2[$j];
    }


    return ($long ? "$difference $periods[$j]" : "$difference$periods[$j]");
}




function getItemFromDate($date, $item)
{
    return date($item, strtotime($date));
}

function twoYears_seconds()
{
    return 60*60*24*365*2;
}





//================================================================================================================================================================
// Ads
//================================================================================================================================================================

function ad($size)
{
    switch($size)
    {
        case 'banner':
            return '<script async src="http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-6181558881748194"
     data-ad-slot="6463016065"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
    }
    return FALSE;
}





//================================================================================================================================================================
// Arrays
//================================================================================================================================================================

function arrayDropDown($array, $selected = '', $textPrepend = '', $textAppend = '')
{
    $options    = '';
    foreach($array as $val)
        $options    .= '<option value="' . $val . '"' . ($selected == $val ? ' selected="selected"' : '') . '>' . $textPrepend . $val . $textAppend . '</option>';

    return $options;
}

function arrayRadioSelect($fieldName, $array, $selected = '')
{
    $options = '';
    foreach($array as $val => $name) $options    .= '<label class="with_input radio"><input type="radio" value="' . $val . '" class="my_answer" name="' . $fieldName . '">' . $name . '.</label>';
    return $options;
}

function arrayCheckboxSelect($fieldName, $array, $selected = '')
{
    $options = '';
    foreach($array as $val => $name) $options    .= '<label class="with_input checkbox"><input type="checkbox" value="' . $val . '" class="my_answer" name="' . $fieldName . '">' . $name . '.</label>';
    return $options;
}

function associativeArrayDropdown($array, $selected = '')
{
    $options    = '';
    foreach($array as $val => $name)
        $options    .= '<option value="' . $val . '"' . ($selected == $val ? ' selected="selected"' : '') . '>' . $name . '</option>';

    return $options;
}

function removeFromArrayByValue($array, $delete)
{
    foreach($array as $key => $val)
        if($val == $delete)
            unset($array[$key]);

    return $array;
}







//================================================================================================================================================================
// Misc
//================================================================================================================================================================
function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}




function post_without_wait($url, $params)
{
    foreach ($params as $key => &$val) {
        if (is_array($val)) $val = implode(',', $val);
        $post_params[] = $key.'='.urlencode($val);
    }
    $post_string = implode('&', $post_params);

    $parts=parse_url($url);

    $fp = fsockopen($parts['host'],
        isset($parts['port'])?$parts['port']:80,
        $errno, $errstr, 30);

    $out = "POST ".$parts['path']." HTTP/1.1\r\n";
    $out.= "Host: ".$parts['host']."\r\n";
    $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $out.= "Content-Length: ".strlen($post_string)."\r\n";
    $out.= "Connection: Close\r\n\r\n";
    if (isset($post_string)) $out.= $post_string;

    fwrite($fp, $out);
    fclose($fp);
}