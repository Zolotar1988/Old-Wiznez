<div id="loading_bar_wrap" style="display: none;"><div id="loading_bar" class="cf"><img src="/assets/<?php echo $this->framework->templateVersion; ?>/images/site/preloader.gif" id="MetroPreLoader_crazyloader"><span>Laster...</span></div></div>

<div id="top_site_notification" style="top:auto;bottom:0"></div>

<div id="lightbox">
    <div id="lightbox-container" class="cf">
        <div id="lightbox-close" onclick="lightbox.close()"></div>
        <div id="lightbox-inner"></div>
    </div>
</div>

<?php
if(isset($_COOKIE['notificationCookie']))
{
    $notificationMsg = json_decode($_COOKIE['notificationCookie']);
    setcookie("notificationCookie", '', time() - 3600, "/");
    ?>
<script>topSiteAlert('<?php echo $notificationMsg->message; ?>', '<?php echo $notificationMsg->type; ?>')</script>
<?php
}

if(isset($_COOKIE['lightboxCookie']))
{
    $lightboxCookieCookie = json_decode($_COOKIE['lightboxCookie']);
    setcookie("lightboxCookie", '', time() - 3600, "/");
    ?>
    <script type="text/javascript">lightbox.open_url('<?php echo $lightboxCookieCookie->url; ?>');</script>
<?php
}