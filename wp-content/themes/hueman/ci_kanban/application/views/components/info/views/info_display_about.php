<?php
function info_aboutUs_userRow($userId, $info)
{
    $ci =& get_instance();
    ?>
    <div class="cf info_user_item">
        <div class="left"><?php echo $ci->user->thumb_large($userId, TRUE); ?></div>
        <div class="right">
            <div class="top"><?php echo $ci->user->username($userId, TRUE) . ' · ' . $ci->user->age_obj($userId) . ' år'; ?></div>
            <div class="bottom"><?php echo $ci->user->username($userId, TRUE) . ' ' . $info; ?></div>
        </div>
    </div>
    <?php
}
?>

<div class="wrap">
    <div class="about_us block">
        <h3>About us</h3>
        <p>Decate is run and managed by a small, dedicated team of founders and volunteers, whom none of this would be possible without.</p>

        <div class="facepile">
            <h3>Management</h3>
            <?php info_aboutUs_userRow(1, "is the founder, manager, developer and designer of Decate. He has built Decate (and soon it's app) alone, from the ground up.<br>Currently living in Eastern Europe for business reasons. Fluent in Portuguese, Spanish, English, Norwegian, soon French and perhaps Italian in the future. A boring, quiet person."); ?>
            <h3>Support</h3>
            <?php foreach($this->user->support_list() as $user) info_aboutUs_userRow($user, 'is a support-level moderator of Decate. Support level indicating that this moderator has shown to be trustworthy, reliable and who has put in a lot of time with helping make Decate better. You are likely to get a lot of help when contacting this moderator.'); ?>
            <h3>Moderators</h3>
            <?php foreach($this->user->mod_list() as $user) info_aboutUs_userRow($user, 'is a moderator of Decate. Moderators are one of the most important groups of people who make Decate great and keep it running smoothly. They are trustworthy, reliable and eager to help. You may contact a moderator if you have any question regarding the site.'); ?>
        </div>
    </div>
</div>