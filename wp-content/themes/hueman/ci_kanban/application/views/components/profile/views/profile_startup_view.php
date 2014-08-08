<script>$("body").attr('id', 'component-profile')</script>
<?php
$headerModule['avatar']     = $userData->thumb_large;
$headerModule['title']      = $userData->name;
$headerModule['meta']       = $userData->city . ', ' . $userData->country . ' · ' . '<a href="' . $userData->url_website . '" target="_blank">' . $userData->url_website . '</a>';
$headerModule['actions']    = '<a href="#" class="btn cta position_applyBtn">Apply for position</a><div class="cf"></div><a href="#" class="btn transparent">Company profile</a>';
$headerModule['actions']    = '';
$this->load->view('components/profile/modules/profile_header_module', $headerModule);
?>

<div class="wrap" id="listing_info">

    <div class="row">
        <div class="large-4 columns">
            <div class="block">
                <div class="title">Key info</div>
                <div class="body keyValContainer">
                    <div class="keyVal_row"><div class="key">Industry</div><div class="val"><?php echo $profile_fields_config['startup']['industry']['options'][$userData->industry]; ?></div></div>
                    <div class="keyVal_row"><div class="key">Description</div><div class="val"><?php echo $userData->pitch; ?></div></div>
                </div>
            </div>

            <div class="block">
                <div class="title">Contact</div>
                <div class="body keyValContainer">
                    <div class="keyVal_row"><div class="key">Email</div><div class="cf"></div><div class="val"><?php echo ($this->user->id ? $userData->username : '*********'); ?></div></div>
                    <?php if($userData->url_linkedin) { ?><div class="keyVal_row"><div class="key">LinkedIn</div><div class="cf"></div><div class="val"><a href="<?php echo $userData->url_linkedin; ?>" target="_blank"><?php echo $userData->url_linkedin; ?></a></div></div><?php } ?>
                    <?php if($userData->url_twitter) { ?><div class="keyVal_row"><div class="key">Twitter</div><div class="cf"></div><div class="val"><a href="<?php echo $userData->url_twitter; ?>" target="_blank"><?php echo $userData->url_twitter; ?></a></div></div><?php } ?>
                    <?php if($userData->url_facebook) { ?><div class="keyVal_row"><div class="key">Facebook</div><div class="cf"></div><div class="val"><a href="<?php echo $userData->url_facebook; ?>" target="_blank"><?php echo $userData->url_facebook; ?></a></div></div><?php } ?>
                </div>
            </div>
        </div>

        <div class="large-8 columns">
            <div class="item_row_container">

                <?php
                if($this->framework->get_singleAccessVariable('showCreateListingForm')) { echo '<div id="profileListingContainerFor-new">'; $this->load->view('components/profile/modules/profile_startup_listing_row_editable', array('isEditing' => FALSE)); echo '</div>'; }

                foreach($listings as $listing)
                {
                    echo '<div id="profileListingContainerFor-' . $listing->id . '">'; $this->load->view('components/profile/modules/profile_startup_listing_row', array('listing' => $listing)); echo '</div>';
                    if($this->user->id == $userData->id) $this->load->view('components/profile/modules/profile_startup_listing_row_editable', array('listing' => $listing, 'isEditing' => TRUE));
                }
                ?>

            </div>
        </div>
    </div>

</div>