<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function placead($adId = '', $currPage)
{
    if($currPage == 'askme' || $currPage == 'messages')
    {
        switch($adId)
        {
            case "side1":
                $advert = '<script type="text/javascript"><!--google_ad_client = "pub-3624697343955506";/* 160x600, created 7/20/10 */google_ad_slot = "0707348323";google_ad_width = 160;google_ad_height = 600;//--></script><script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                break;

            case "side2":
                $advert = '<script type="text/javascript"><!--google_ad_client = "pub-3624697343955506";/* 160x600, created 7/20/10 */google_ad_slot = "0707348323";google_ad_width = 160;google_ad_height = 600;//--></script><script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                break;

            case "top":
                $advert = '<script type="text/javascript"><!--google_ad_client = "ca-pub-3624697343955506";/* 728x90, created 8/   22/10 */google_ad_slot = "9449777358";google_ad_width = 728;google_ad_height = 90;//--></script><script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                break;

        }
    }
    else
    {
        switch($adId)
        {
            case "side1":
                $advert = '<script type="text/javascript"><!--google_ad_client = "pub-3624697343955506";/* 160x600, created 7/20/10 */google_ad_slot = "0707348323";google_ad_width = 160;google_ad_height = 600;//--></script><script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                break;

            case "side2":
                $advert = '<script type="text/javascript"><!--google_ad_client = "pub-3624697343955506";/* 160x600, created 7/20/10 */google_ad_slot = "0707348323";google_ad_width = 160;google_ad_height = 600;//--></script><script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                break;

            case "top":
                $advert = '<script type="text/javascript"><!--google_ad_client = "ca-pub-3624697343955506";/* 728x90, created 8/   22/10 */google_ad_slot = "9449777358";google_ad_width = 728;google_ad_height = 90;//--></script><script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>';
                break;
        }
    }

    return $advert;
}



