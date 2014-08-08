<script>$("body").attr('id', 'component-profile')</script>
<?php
$headerModule['avatar']     = $userData->thumb_large;
$headerModule['title']      = $userData->name;
$headerModule['meta']       = $userData->city . ', ' . $userData->country;

$userId=$userData->id;

if(!$hasInvited)
{
    $headerModule['actions']    = ($this->user->id == $userData->id ? '' : '<a href="#" onclick="listings.user.request_inviteToApply(' . $userData->id . '); return false;" class="btn transparent">Invite to apply</a>');
}
else
{
    $headerModule['actions']    = ($this->user->id == $userData->id ? '' : '<a href="#" onclick="" style="cursor: default" class="btn cta">&#10004 Invited</a>');
}

//echo '<a href="#" onclick="" style="cursor: default;display: none" id="asdjsdiuadshasduhi" class="btn cta">Already invited</a>';

$this->load->view('components/profile/modules/profile_header_module', $headerModule);
?>

<div class="wrap" id="listing_info">

    <div class="row">

        <div class="large-4 columns">
            <div class="block">
                <div class="title">Key info</div>
                <div class="body keyValContainer">
                    <div class="keyVal_row"><div class="key">Age</div><div class="val"><?php echo $userData->age; ?></div></div>
                    <div class="keyVal_row"><div class="key">Gender</div><div class="val"><?php echo $profile_fields_config['intern']['gender']['options'][$userData->gender]; ?></div></div>
                    <div class="keyVal_row"><div class="key">Profession</div><div class="val"><?php echo (isset($profile_fields_config['intern']['profession']['options'][$userData->profession]) ? $profile_fields_config['intern']['profession']['options'][$userData->profession] : ''); ?></div></div>
                </div>
            </div>

            <div class="block">
                <div class="title">Contact</div>
                <div class="body keyValContainer">
                    <div class="keyVal_row"><div class="key">Email</div><div class="cf"></div><div class="val"><?php echo $userData->username; ?></div></div>
                    <?php if($userData->url_linkedin) { ?><div class="keyVal_row"><div class="key">LinkedIn</div><div class="cf"></div><div class="val"><a href="<?php echo $userData->url_linkedin; ?>" target="_blank"><?php echo $userData->url_linkedin; ?></a></div></div><?php } ?>
                    <?php if($userData->url_twitter) { ?><div class="keyVal_row"><div class="key">Twitter</div><div class="cf"></div><div class="val"><a href="<?php echo $userData->url_twitter; ?>" target="_blank"><?php echo $userData->url_twitter; ?></a></div></div><?php } ?>
                    <?php if($userData->url_facebook) { ?><div class="keyVal_row"><div class="key">Facebook</div><div class="cf"></div><div class="val"><a href="<?php echo $userData->url_facebook; ?>" target="_blank"><?php echo $userData->url_facebook; ?></a></div></div><?php } ?>
                </div>
            </div>
        </div>


        <div class="large-8 columns">
            <div class="block">
                <div class="title">About me</div>
                <div class="body keyValContainer">
                    <div class="keyVal_row"><div class="key">Description</div><div class="val"><?php echo $userData->question_description; ?></div></div>
                    <div class="keyVal_row"><div class="key">Why I want to join a startup</div><div class="val"><?php echo $userData->question_whyWantIntern; ?></div></div>
                    <div class="keyVal_row"><div class="key">What separates me from others</div><div class="val"><?php echo $userData->question_whatSeparates; ?></div></div>
                </div>
            </div>
        </div>
    </div>

</div>