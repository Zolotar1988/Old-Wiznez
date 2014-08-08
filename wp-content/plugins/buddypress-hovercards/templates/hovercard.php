<?php $randId = md5(microtime()); ?>
<?php if ( bp_has_members( "include={$_POST['userid']}&max=1") ) : ?>
    <?php while ( bp_members() ) : bp_the_member();
        global $members_template; ?>
        <div id="user_card-<?php echo $randId; ?>" class="cf user_card">
            <div class="cardFace">
                <div class="left">
                    <div class="avatar"><a href="<?php echo '/members/' . get_userdata(bp_get_member_user_id())->user_nicename;?>"><img src="<?php echo bp_core_fetch_avatar( array( 'item_id' => bp_get_member_user_id(), 'type' => 'full', 'width' => 100, 'height' => 100, 'html' => false ) ); ?>"></a></div>
                    <div class="social_links">
                        <a href="<?php bp_profile_field_data( array('field' => 'facebook', 'user_id' => bp_get_member_user_id()) );?>"><i class="fa fa-facebook" style="color:grey;render:block;"></i></a>
                        <a href="<?php bp_profile_field_data( array('field' => 'twitter', 'user_id' => bp_get_member_user_id()) );?>"><i class="fa fa-twitter" style="color:grey;render:block;"></i></a>
                        <a href="<?php bp_profile_field_data( array('field' => 'linkedin', 'user_id' => bp_get_member_user_id()) );?>"><i class="fa fa-linkedin" style="color:grey;render:block;"></i></a>
                    </div>
                </div>

                <div class="right">
                    <div class="top cf">
                        <div class="keyVal_row cf"><div class="key">Name</div><div class="val"><?php bp_member_name(); ?></div></div>
                        <div class="keyVal_row cf"><div class="key">Title</div><div class="val"><?php bp_profile_field_data( array('field' => 'title', 'user_id' => bp_get_member_user_id()) );?></div></div>
                        <div class="keyVal_row cf"><div class="key">Company</div><div class="val"><?php bp_profile_field_data( array('field' => 'company', 'user_id' => bp_get_member_user_id()) );?></div></div>
                    </div>

                        <div class="bottom cf">
                            <div class="keyVal_row cf"><div class="key">P:</div><div class="val"><?php bp_profile_field_data( array('field' => 'mobile', 'user_id' => bp_get_member_user_id()) );?></div></div>
                            <div class="keyVal_row cf"><div class="key">M:</div><div class="val"><?php bp_member_user_email();?></div></div>
                            <div class="keyVal_row cf"><div class="key">A:</div><div class="val"><?php bp_profile_field_data( array('field' => 'address', 'user_id' => bp_get_member_user_id()) );?></div></div>
                            <div class="keyVal_row cf"><div class="key">W:</div><div class="val"><?php bp_profile_field_data( array('field' => 'website', 'user_id' => bp_get_member_user_id()) );?></div></div>
                        </div>
                    </div>
                </div>

            <div class="cardFace" style="display: none">
                <div class="about_user"><?php bp_profile_field_data( array('field' => 'About', 'user_id' => bp_get_member_user_id()) );?></div>
            </div>

            <div class="flip_card"><a onclick="flipCard_<?php echo $randId; ?>(); return false;" href="#">&#10095;</a></div>
            <script>
                function flipCard_<?php echo $randId; ?>()
                {
                    jQuery("#user_card-<?php echo $randId; ?> .cardFace").toggle();
                }
            </script>
        </div>
    <?php endwhile; ?>
<?php endif; ?>