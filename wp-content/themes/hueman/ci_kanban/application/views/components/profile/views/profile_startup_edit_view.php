<script>$("body").attr('id', 'component-profileEdit')</script>
<div class="block_heading"><?php if(!$this->user->currentUserData->data_filled) echo 'Welcome, ' . $this->user->currentUserData->name . '! Tell us a bit about your startup.'; ?></div>
<div class="block form_block">
    <div class="title">W’ll use this information to create your profile page</div>
    <div class="body">
        <form method="POST" action="/profile/profile_actions_controller/edit">
            <?php $this->load->view('modules/imageupload/views/imageupload_view', array('imageUploadConfig' => $imageUploadConfig)); ?>
            <?php $this->load->view('renderers/tagsystem/views/renderer_tag_selection', array('labelTitle' => 'Skills needed'));  ?>
            <?php echo generateInputFields($this->user->profileTypes_fields($this->user->currentUserData->profile_type), $this->user->currentUserData); ?>
            <?php echo generate_submitButton(($this->user->currentUserData->data_filled ? 'Save your profile' : 'Go to application creation')); ?>

            <script type="application/javascript">
                $("#profile_pitch").parent().find('label').append(' - <span style="font-weight:normal;" class="characterCounter" id="characterCounter-for-profile_pitch"><?php echo ($this->user->currentUserData->pitch ? strlen($this->user->currentUserData->pitch) : '0'); ?>/150</span>')
                $("#profile_pitch").attr('maxlength', 150).attr('onkeyup', "forms_updateCharacterCount('profile_pitch')");
            </script>

        </form>
    </div>
</div>

<?php if(!$this->user->currentUserData->data_filled) { ?><script type="application/javascript">$("#main_header").hide(); $("#header_float_placeholder").hide();  $( document ).ready(function() { $("#site_footer").hide(); });</script><?php } ?>

<script type="text/javascript" charset="utf-8">(function($){ $(function(){ $('#profile_country').selectToAutocomplete(); }); })(jQuery);</script>